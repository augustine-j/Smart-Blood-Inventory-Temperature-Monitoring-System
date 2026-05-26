<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BloodBagResource;
use App\Services\InventoryAnalyticsService;

class InventoryAnalyticsController extends Controller
{
    public function expirySummary(InventoryAnalyticsService $service)
    {
        $summary = $service->expirySummary();

        return response()->json([
            'expiring_within_24_hours_count' => $summary['expiring_within_24_hours_count'],
            'expired_inventory_count' => $summary['expired_inventory_count'],
            'near_risk_inventory_percentage' => $summary['near_risk_inventory_percentage'],
            'expiring_within_24_hours' => BloodBagResource::collection($summary['expiring_within_24_hours']),
            'expired_inventory' => BloodBagResource::collection($summary['expired_inventory']),
        ]);
    }
}
