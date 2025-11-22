<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Location;
use App\Models\ParkingSession;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Detailed report of sessions
    public function sessionsReport()
    {
        try {
            $locations = Location::all();
            $buildings = Building::all();
            $filterAction = route('sessionsReportFilter');
            return view('sessions_report.index', compact('locations', 'buildings', 'filterAction'));
        } catch (\Throwable $th) {
            return $th->getMessage();
            // return redirect()->back()->with('error', $th->getMessage());
        }
    }

    //sessions Report Filter
    public function sessionsReportFilter(Request $request)
    {
        try {
            $locations = Location::all();
            $buildings = Building::all();
            $filterAction = route('sessionsReportFilter');

            $formData = $request->all();

            $from_date      = $formData['from_date'] ?? null;
            $to_date        = $formData['to_date'] ?? null;
            $location_id    = $formData['location'] ?? null;
            $building_id    = $formData['building'] ?? null;
            $status         = $formData['status'] ?? null;

            $page = $request->input('page', 1);
            $perPage = 25; // Rows per page

            // Fetch filtered parking sessions based on the provided criteria
            $query = ParkingSession::query();

            $query = $query->with(['location', 'building', 'entryAccessPoint', 'exitAccessPoint',  'vehicleMaster']);

            if ($location_id != 'All' && $location_id) {
                $query->where('location_id', $location_id);
            }

            if ($building_id != 'All' && $building_id) {
                $query->where('building_id', $building_id);
            }

            if ($from_date) {
                $query->whereDate('in_time', '>=', $from_date);
            }

            if ($to_date) {
                $query->whereDate('in_time', '<=', $to_date);
            }

            if ($status != 'All' && is_numeric($status)) {
                $statusInt = (int) $status;
                $query->where('status', $statusInt);
            }

            // Paginate results
            $parkingSessions = $query->paginate($perPage, ['*'], 'page', $page);

            // Append filter parameters to pagination links
            $parkingSessions->appends(request()->query());

            $report_html = view('sessions_report.sessions_filter_list', compact('parkingSessions'))->render();
            $pagination_html = (string)$parkingSessions->links('pagination::bootstrap-4');

            return response()->json([
                'status'            => 'success', 
                'report_html'       => $report_html,
                'pagination_html'   => $pagination_html,
                'total'             => $parkingSessions->total(),
                'current_page'      => $parkingSessions->currentPage(),
                'per_page'          => $parkingSessions->perPage(),
            ]);

        } catch (\Throwable $th) {
            return $th->getMessage();
            // return redirect()->back()->with('error', $th->getMessage());
        }
    }

    //exportSessionsReport
    public function exportSessionsReport(Request $request)
    {
        try {
            $formData = $request->all();

            $from_date      = $formData['from_date'] ?? null;
            $to_date        = $formData['to_date'] ?? null;
            $location_id    = $formData['location'] ?? null;
            $building_id    = $formData['building'] ?? null;
            $status         = $formData['status'] ?? null;

            // Fetch filtered parking sessions based on the provided criteria
            $query = ParkingSession::query();

            $query = $query->with(['location', 'building', 'entryAccessPoint', 'exitAccessPoint',  'vehicleMaster']);

            if ($location_id != 'All' && $location_id) {
                $query->where('location_id', $location_id);
            }

            if ($building_id != 'All' && $building_id) {
                $query->where('building_id', $building_id);
            }

            if ($from_date) {
                $query->whereDate('in_time', '>=', $from_date);
            }

            if ($to_date) {
                $query->whereDate('in_time', '<=', $to_date);
            }

            if ($status != 'All' && is_numeric($status)) {
                $statusInt = (int) $status;
                $query->where('status', $statusInt);
            }

            $parkingSessions = $query->get();

            // Generate CSV
            $filename = 'sessions_report_' . date('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $columns = ['Sl.No.', 'In Time', 'Out Time', 'Location', 'Building', 'Entry Access Point Name', 'Exit Access Point Name', 'Plate', 'Status', 'Duration'];

            $callback = function() use ($parkingSessions, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($parkingSessions as $key => $session) {

                    $duration = 'N/A';
                    if($session->status == 2 && $session->out_time) {
                            
                        $totalMinutes = $session->out_time->diffInMinutes($session->in_time)*(-1);
                        $hours = intdiv($totalMinutes, 60);
                        $mins = $totalMinutes % 60;
                        
                        $duration = '';
                        if ($hours > 0) {
                            $duration .= $hours . ' ' . ($hours === 1 ? 'hr' : 'hrs');
                        }
                        if ($mins > 0) {
                            if ($duration !== '') {
                                $duration .= ' ';
                            }
                            $duration .= $mins . ' min';
                        }
                        if ($duration === '') {
                            $duration = '0 min';
                        }
                    }

                    $row = [
                        $key + 1,
                        $session->in_time->format('d M Y, h:i A'),
                        $session->out_time ? $session->out_time->format('d M Y, h:i A') : 'N/A',
                        $session->location->name,
                        $session->building->name,
                        $session->entryAccessPoint->name,
                        $session->exitAccessPoint ? $session->exitAccessPoint->name : 'N/A',
                        $session->vehicleMaster->plate_code.' '.$session->vehicleMaster->plate_number.' '.$session->vehicleMaster->emirates,
                        $session->status == 1 ? 'Active' : 'Closed',
                        $duration
                        
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (\Throwable $th) {
            return $th->getMessage();
            // return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
