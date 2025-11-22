<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Location;
use App\Models\ParkingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class Dashboard extends Controller
{
    /**
     * Format duration in minutes to human-friendly string (e.g., "1 hr 35 min")
     *
     * @param int $minutes Total minutes
     * @return string Formatted duration
     */
    private function formatDuration($minutes)
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' ' . ($hours === 1 ? 'hr' : 'hrs');
        }
        if ($mins > 0) {
            $parts[] = $mins . ' min';
        }

        return !empty($parts) ? implode(' ', $parts) : '0 min';
    }

    //Main Dashboard Page
    public function index()
    {
        try {
            $locations = Location::all();
            $buildings = Building::all();
            $filterAction = route('dashboardSummary');
            return view('dashboard.index', compact('locations', 'buildings', 'filterAction'));
        } catch (\Throwable $th) {
            return $th->getMessage();
            // return redirect()->back()->with('error', $th->getMessage());
        }
        
    }

    //Filter for Dashboard Summary
    public function dashboardSummary(Request $request)
    {
        try {

            $formData = $request->all();

            $validator = Validator::make($formData, [
                'from_date'     => 'nullable|date',
                'to_date'       => 'nullable|date',
                'location_id'   => 'nullable',
                'building_id'   => 'nullable',
                'status'        => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }

            $from_date      = $formData['from_date'] ?? null;
            $to_date        = $formData['to_date'] ?? null;
            $location_id    = $formData['location'] ?? null;
            $building_id    = $formData['building'] ?? null;
            $status         = $formData['status'] ?? null;

            // Build base query
            $parkingSessionsQuery = ParkingSession::query();

            // Parse and apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                try {
                    $from   = Carbon::parse($from_date);
                    $to     = Carbon::parse($to_date);
                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid date format'], 422);
                }

                if ($from->greaterThan($to)) {
                    // swap
                    [$from, $to] = [$to, $from];
                }

                $parkingSessionsQuery->whereBetween('in_time', [$from->toDateTimeString(), $to->toDateTimeString()]);
            } else {
                if (!empty($from_date)) {
                    try { $from = Carbon::parse($from_date); } catch (\Exception $e) { return response()->json(['status'=>'error','message'=>'Invalid from_date'],422); }
                    $parkingSessionsQuery->where('in_time', '>=', $from->toDateTimeString());
                }

                if (!empty($to_date)) {
                    try { $to = Carbon::parse($to_date); } catch (\Exception $e) { return response()->json(['status'=>'error','message'=>'Invalid to_date'],422); }
                    $parkingSessionsQuery->where('in_time', '<=', $to->toDateTimeString());
                }
            }

            if ($location_id != 'All' && !is_null($location_id)) {
                $parkingSessionsQuery->where('location_id', $location_id);
            }

            if ($building_id != 'All' && !is_null($building_id)) {
                $parkingSessionsQuery->where('building_id', $building_id);
            }

            // If status filter selected, return only that status count (and zero for the other)
            // If status is selected (1 or 2) apply that filter, otherwise treat as 'All' and return both counts
            if (!is_null($status) && is_numeric($status)) {
                $statusInt = (int) $status;
                $count = (clone $parkingSessionsQuery)->where('status', $statusInt)->count();
                if ($statusInt === 1) {
                    $total_active_sessions = $count;
                    $total_closed_sessions = 0;
                } else {
                    $total_active_sessions = 0;
                    $total_closed_sessions = $count;
                }
            } else {
                $total_active_sessions = (clone $parkingSessionsQuery)->where('status', 1)->count();
                $total_closed_sessions = (clone $parkingSessionsQuery)->where('status', 2)->count();
            }


            //Average Parking Duration
            $avg_parking_duration = 0;
            $avg_parking_duration_formatted = 'N/A';
            if ($total_closed_sessions > 0) {
                $total_duration = (clone $parkingSessionsQuery)
                    ->whereNotNull('out_time')
                    ->where('status', 2)
                    ->get()
                    ->sum(function ($session) {
                        return $session->out_time->diffInMinutes($session->in_time);
                    });
                $avg_parking_duration = $total_duration / $total_closed_sessions;
                $avg_parking_duration_formatted = $this->formatDuration((int)$avg_parking_duration);
            }

            // Top Vehicle by Session Count
            $top_vehicle = null;
            $top_vehicle_sessions = 0;
            $topVehicleRecord = (clone $parkingSessionsQuery)
                ->join('vehicle_masters', 'parking_sessions.vehicle_master_id', '=', 'vehicle_masters.id')
                ->groupBy('vehicle_masters.id', 'vehicle_masters.plate_code', 'vehicle_masters.plate_number', 'vehicle_masters.emirates')
                ->selectRaw('vehicle_masters.id, vehicle_masters.plate_code, vehicle_masters.plate_number, vehicle_masters.emirates, COUNT(parking_sessions.id) as session_count')
                ->orderByDesc('session_count')
                ->first();

            if ($topVehicleRecord) {
                $plate = $topVehicleRecord->plate_code . ' ' . $topVehicleRecord->plate_number . ' ' . $topVehicleRecord->emirates;
                $top_vehicle = [
                    'plate' => $plate,
                    'session_count' => $topVehicleRecord->session_count,
                ];
                $top_vehicle_sessions = $topVehicleRecord->session_count;
            }


            return response()->json(
                [
                'status'                => 'success', 
                'message'               => 'Dashboard Summary Fetched Successfully',
                'total_active_sessions' => $total_active_sessions,
                'total_closed_sessions' => $total_closed_sessions,
                'avg_parking_duration_formatted' => $avg_parking_duration_formatted,
                'top_vehicle' => $top_vehicle,
            ],
            200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // Hourly Sessions Chart Data
    public function dailyHourlySessionTracker(Request $request)
    {
        try {
            $formData = $request->all();

            $validator = Validator::make($formData, [
                'from_date'     => 'nullable|date',
                'to_date'       => 'nullable|date',
                'location_id'   => 'nullable',
                'building_id'   => 'nullable',
                'status'        => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }

            $from_date      = $formData['from_date'] ?? null;
            $to_date        = $formData['to_date'] ?? null;
            $location_id    = $formData['location'] ?? null;
            $building_id    = $formData['building'] ?? null;
            $status         = $formData['status'] ?? null;

            // Build base query
            $parkingSessionsQuery = ParkingSession::query();

            // Parse and apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                try {
                    $from   = Carbon::parse($from_date);
                    $to     = Carbon::parse($to_date);
                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid date format'], 422);
                }

                if ($from->greaterThan($to)) {
                    [$from, $to] = [$to, $from];
                }

                $parkingSessionsQuery->whereBetween('in_time', [$from->toDateTimeString(), $to->toDateTimeString()]);
            } else {
                if (!empty($from_date)) {
                    try { $from = Carbon::parse($from_date); } catch (\Exception $e) { return response()->json(['status'=>'error','message'=>'Invalid from_date'],422); }
                    $parkingSessionsQuery->where('in_time', '>=', $from->toDateTimeString());
                }

                if (!empty($to_date)) {
                    try { $to = Carbon::parse($to_date); } catch (\Exception $e) { return response()->json(['status'=>'error','message'=>'Invalid to_date'],422); }
                    $parkingSessionsQuery->where('in_time', '<=', $to->toDateTimeString());
                }
            }

            if ($location_id != 'All' && !is_null($location_id)) {
                $parkingSessionsQuery->where('location_id', $location_id);
            }

            if ($building_id != 'All' && !is_null($building_id)) {
                $parkingSessionsQuery->where('building_id', $building_id);
            }

            if (!is_null($status) && is_numeric($status)) {
                $parkingSessionsQuery->where('status', (int)$status);
            }

            // Group by hour of in_time
            $hourlyData = (clone $parkingSessionsQuery)
                ->selectRaw('HOUR(in_time) as hour, COUNT(*) as session_count')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            // Initialize array with all hours (0-23) set to 0
            $hours = array_fill(0, 24, 0);
            foreach ($hourlyData as $record) {
                $hours[$record->hour] = $record->session_count;
            }

            return response()->json(
                [
                'status'        => 'success',
                'message'       => 'Hourly Session Data Fetched Successfully',
                'hours'         => $hours, // array indexed 0-23
                'data'          => array_values($hours), // just the counts
            ],
            200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
