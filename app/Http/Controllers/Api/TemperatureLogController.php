<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTemperatureLogRequest;
use App\Http\Resources\TemperatureLogResource;
use App\Jobs\ProcessTemperatureAlertJob;
use App\Models\Refrigerator;
use App\Models\TemperatureLog;
use Illuminate\Http\Request;

class TemperatureLogController extends Controller
{
    public function store(StoreTemperatureLogRequest $request)
    {
        $validated = $request->validated();

        $temperatureLog = TemperatureLog::create([
            'refrigerator_id' => $validated['refrigerator_id'],
            'temperature' => $validated['temperature'],
            'status' => TemperatureLog::statusForTemperature((float) $validated['temperature']),
            'recorded_at' => $validated['recorded_at'] ?? now(),
        ]);

        if ($temperatureLog->status === 'critical') {
            ProcessTemperatureAlertJob::dispatch($temperatureLog->refrigerator_id);
        }

        return TemperatureLogResource::make(
            $temperatureLog->load('refrigerator')
        )->additional([
            'message' => 'Temperature log stored successfully',
        ]);
    }

    public function refrigeratorRisk(Request $request, Refrigerator $refrigerator)
    {
        $date = $request->date('date', now()->toDateString());

        $logs = $refrigerator->temperatureLogs()
            ->whereDate('recorded_at', $date)
            ->get();

        $totalMinutes = $logs->count();
        $unsafeMinutes = $logs->whereIn('status', ['warning', 'critical'])->count();

        return response()->json([
            'refrigerator' => [
                'id' => $refrigerator->id,
                'name' => $refrigerator->name,
                'code' => $refrigerator->code,
            ],
            'date' => $date,
            'daily_average_temperature' => round((float) $logs->avg('temperature'), 2),
            'highest_temperature' => $logs->max('temperature'),
            'lowest_temperature' => $logs->min('temperature'),
            'total_minutes' => $totalMinutes,
            'unsafe_minutes' => $unsafeMinutes,
            'risk_percentage' => $totalMinutes > 0
                ? round(($unsafeMinutes / $totalMinutes) * 100, 2)
                : 0,
        ]);
    

    }
}
