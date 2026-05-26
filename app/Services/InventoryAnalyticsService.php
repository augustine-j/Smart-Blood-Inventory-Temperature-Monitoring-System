<?php

namespace App\Services;

use App\Models\BloodBag;
use App\Models\Refrigerator;
use App\Models\TemperatureAlert;
use App\Models\TemperatureLog;

class InventoryAnalyticsService
{
    public function expirySummary(): array
    {
        $totalBags = BloodBag::count();

        $expiringWithin24Hours = BloodBag::with('refrigerator.bloodBank')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->whereDate('expiry_date', '<=', now()->addDay()->toDateString())
            ->where('status', '!=', 'expired')
            ->get();

        $expiredInventory = BloodBag::with('refrigerator.bloodBank')
            ->where(function ($query) {
                $query->whereDate('expiry_date', '<', now()->toDateString())
                    ->orWhere('status', 'expired');
            })
            ->get();

        return [
            'expiring_within_24_hours_count' => $expiringWithin24Hours->count(),
            'expired_inventory_count' => $expiredInventory->count(),
            'near_risk_inventory_percentage' => $totalBags > 0
                ? round(($expiringWithin24Hours->count() / $totalBags) * 100, 2)
                : 0,
            'expiring_within_24_hours' => $expiringWithin24Hours,
            'expired_inventory' => $expiredInventory,
        ];
    }

    public function refrigeratorHealthScore(): float
    {
        $totalLogsToday = TemperatureLog::whereDate('recorded_at', today())->count();

        if ($totalLogsToday === 0) {
            return 100;
        }

        $unsafeLogsToday = TemperatureLog::whereDate('recorded_at', today())
            ->whereIn('status', ['warning', 'critical'])
            ->count();

        $riskPercentage = ($unsafeLogsToday / $totalLogsToday) * 100;

        return round(100 - $riskPercentage, 2);
    }

    public function dashboard(): array
    {
        return [
            'total_blood_bags' => BloodBag::count(),

            'available_stock_by_blood_group' => BloodBag::query()
                ->selectRaw('blood_group, COUNT(*) as bags_count, SUM(quantity_ml) as total_quantity_ml')
                ->where('status', 'available')
                ->groupBy('blood_group')
                ->orderBy('blood_group')
                ->get(),

            'refrigerator_health_score' => $this->refrigeratorHealthScore(),

            'critical_temperature_alerts' => TemperatureAlert::with('refrigerator.bloodBank')
                ->where('status', 'open')
                ->latest()
                ->limit(10)
                ->get(),

            'average_temperature_today' => round(
                (float) TemperatureLog::whereDate('recorded_at', today())->avg('temperature'),
                2
            ),

            'total_expired_bags' => BloodBag::where(function ($query) {
                $query->whereDate('expiry_date', '<', now()->toDateString())
                    ->orWhere('status', 'expired');
            })->count(),

            'active_refrigerators' => Refrigerator::where('status', 'active')->count(),
        ];
    }
}