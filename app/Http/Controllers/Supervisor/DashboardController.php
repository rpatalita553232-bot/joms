<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\JobOrder;
use App\Models\JobOrderApproval;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pendingCount = JobOrder::where('status', 'pending')->count();
        $inProgressCount = JobOrder::where('status', 'in_progress')->count();
        $completedThisMonthCount = JobOrder::query()
            ->where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        $pendingJobOrders = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'creator'])
            ->where('status', 'pending')
            ->orderByDesc('job_order_id')
            ->get();
        $recentApprovals = JobOrderApproval::query()
            ->with('jobOrder')
            ->orderByDesc('action_date')
            ->limit(5)
            ->get();

        return view('supervisor.dashboard', compact(
            'pendingCount',
            'inProgressCount',
            'completedThisMonthCount',
            'pendingJobOrders',
            'recentApprovals',
        ));
    }
}
