<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodBank;
use App\Models\Refrigerator;

class RelationshipDemoController extends Controller
{
    public function bloodBanks()
    {
        $bloodBanks = BloodBank::query()
            ->with([
                'refrigerators' => function ($query) {
                    $query->withCount('bloodBags');
                },
                'refrigerators.bloodBags',
            ])
            ->withCount('refrigerators')
            ->get();

        return response()->json($bloodBanks);
    }


     public function refrigeratorsWithCriticalLogs(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $refrigerators = Refrigerator::query()
            ->with(['bloodBank'])
            ->withCount([
                'bloodBags',
                'temperatureLogs as critical_logs_count' => function ($query) use ($date) {
                    $query->where('status', 'critical')
                        ->whereDate('recorded_at', $date);
                },
            ])
            ->whereHas('temperatureLogs', function ($query) use ($date) {
                $query->where('status', 'critical')
                    ->whereDate('recorded_at', $date);
            })
            ->get();

        return response()->json([
            'date' => $date,
            'data' => $refrigerators,
        ]);
    }

    
    public function bloodBanksWithAvailableStock()
    {
        $bloodBanks = BloodBank::query()
            ->withCount('refrigerators')
            ->whereHas('refrigerators.bloodBags', function ($query) {
                $query->where('status', 'available');
            })
            ->with([
                'refrigerators' => function ($query) {
                    $query->withCount([
                        'bloodBags as available_bags_count' => function ($bagQuery) {
                            $bagQuery->where('status', 'available');
                        },
                    ]);
                },
            ])
            ->get();

        return response()->json($bloodBanks);
    }

}
