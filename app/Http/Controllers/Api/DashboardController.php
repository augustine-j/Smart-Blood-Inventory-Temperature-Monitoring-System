<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InventoryAnalyticsService;

class DashboardController extends Controller
{
    public function index(InventoryAnalyticsService $service)
    {
        return response()->json($service->dashboard());
    }
}
