<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\JobOrder;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $staffId = Staff::query()->where('user_id', Auth::id())->value('staff_id');

        $assignedJobOrders = $staffId
            ? JobOrder::query()
                ->with(['customer', 'vehicle', 'services'])
                ->where('status', 'in_progress')
                ->whereHas('jobOrderAssignments', fn ($query) => $query->where('staff_id', $staffId))
                ->orderByDesc('job_order_id')
                ->limit(5)
                ->get()
            : collect();

        $inProgressCount = $staffId
            ? JobOrder::query()
                ->where('status', 'in_progress')
                ->whereHas('jobOrderAssignments', fn ($query) => $query->where('staff_id', $staffId))
                ->count()
            : 0;

        $needsRevisionCount = JobOrder::query()
            ->where('created_by', Auth::id())
            ->where('status', 'rejected')
            ->count();
        $pendingCount = JobOrder::query()
            ->where('created_by', Auth::id())
            ->where('status', 'pending')
            ->count();
        $createdJobOrderCount = JobOrder::where('created_by', Auth::id())->count();
        $needsRevisionJobOrders = JobOrder::query()
            ->with(['customer', 'vehicle'])
            ->where('created_by', Auth::id())
            ->where('status', 'rejected')
            ->orderByDesc('job_order_id')
            ->limit(5)
            ->get();
        $createdJobOrders = JobOrder::query()
            ->with(['customer', 'vehicle'])
            ->where('created_by', Auth::id())
            ->orderByDesc('job_order_id')
            ->limit(5)
            ->get();

        return view('mechanic.dashboard', compact(
            'inProgressCount',
            'needsRevisionCount',
            'pendingCount',
            'createdJobOrderCount',
            'assignedJobOrders',
            'needsRevisionJobOrders',
            'createdJobOrders',
        ));
    }
}
