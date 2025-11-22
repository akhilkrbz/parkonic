<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Location;
use App\Models\ParkingSession;
use Illuminate\Http\Request;

class ReportController extends Controller
{
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

    //sessionsReportFilter
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
}
