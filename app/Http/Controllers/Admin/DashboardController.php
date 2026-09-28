<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customerCount = Customer::count();
        $vehicleCount = Vehicle::count();
        $staffCount = Staff::count();
        $serviceCount = Service::count();
        $recentJobOrders = JobOrder::query()
            ->with(['customer', 'vehicle'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'customerCount',
            'vehicleCount',
            'staffCount',
            'serviceCount',
            'recentJobOrders',
        ));
    }
}