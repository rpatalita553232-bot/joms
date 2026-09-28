<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignJobOrderRequest;
use App\Http\Requests\RejectJobOrderRequest;
use App\Models\JobOrder;
use App\Models\JobOrderApproval;
use App\Models\JobOrderAssignment;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOrderController extends Controller
{
    public function allJobOrders(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,approved,rejected,in_progress,completed'],
        ]);

        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'creator', 'jobOrderAssignments.staff.user'])
            ->orderByDesc('job_order_id');

        if ($filters['status'] ?? null) {
            $query->where('status', $filters['status']);
        }

        if ($filters['search'] ?? null) {
            $search = trim($filters['search']);
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('problem_description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });

                if (ctype_digit($search)) {
                    $innerQuery->orWhere('job_order_id', (int) $search);
                }
            });
        }

        return view('supervisor.AJO', ['jobOrders' => $query->get()]);
    }

    public function pendingApprovals(Request $request): View
    {
        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'creator'])
            ->where('status', 'pending')
            ->orderByDesc('job_order_id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('problem_description', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $jobOrders = $query->get();

        return view('supervisor.pending-approvals', compact('jobOrders'));
    }

    public function show(JobOrder $jobOrder): View
    {
        $jobOrder->load([
            'customer',
            'vehicle',
            'services',
            'creator',
            'jobOrderAssignments.staff.user',
            'jobOrderApprovals.approvedBy',
        ]);

        return view('supervisor.AJO', compact('jobOrder'));
    }

    public function approvalHistory(Request $request): View
    {
        $query = JobOrderApproval::query()
            ->with(['jobOrder', 'approvedBy'])
            ->orderByDesc('action_date');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->whereHas('jobOrder.customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        $approvals = $query->get();

        return view('supervisor.approval-history', compact('approvals'));
    }

    public function assignMechanic(Request $request): View
    {
        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'jobOrderAssignments.staff.user'])
            ->where('status', 'approved')
            ->orderByDesc('job_order_id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('problem_description', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $jobOrders = $query->get();
        $mechanics = Staff::query()
            ->with('user')
            ->withCount([
                'jobOrderAssignments as active_job_orders_count' => fn ($query) => $query->whereHas(
                    'jobOrder',
                    fn ($jobOrderQuery) => $jobOrderQuery->where('status', 'in_progress'),
                ),
            ])
            ->whereHas('user', fn ($query) => $query->where('role', 'mechanic'))
            ->orderBy('staff_last')
            ->get();

        return view('supervisor.assign-mechanic', compact('jobOrders', 'mechanics'));
    }

    public function approve(JobOrder $jobOrder): RedirectResponse
    {
        if ($jobOrder->status !== 'pending') {
            return redirect()->route('supervisor.pending-approvals')->with('error', 'This job order is no longer pending.');
        }

        JobOrderApproval::create([
            'job_order_id' => $jobOrder->job_order_id,
            'approved_by' => auth()->id(),
            'status' => 'approved',
            'remarks' => null,
            'action_date' => now(),
        ]);

        $jobOrder->update([
            'status' => 'approved',
        ]);

        return redirect()->route('supervisor.pending-approvals')->with('success', 'Job order approved successfully.');
    }

    public function reject(RejectJobOrderRequest $request, JobOrder $jobOrder): RedirectResponse
    {
        if ($jobOrder->status !== 'pending') {
            return redirect()->route('supervisor.pending-approvals')->with('error', 'This job order is no longer pending.');
        }

        $remarks = $request->input('remarks');

        JobOrderApproval::create([
            'job_order_id' => $jobOrder->job_order_id,
            'approved_by' => auth()->id(),
            'status' => 'rejected',
            'remarks' => $remarks,
            'action_date' => now(),
        ]);

        $jobOrder->update([
            'status' => 'rejected',
            'remarks' => $remarks,
        ]);

        return redirect()->route('supervisor.pending-approvals')->with('success', 'Job order rejected successfully.');
    }

    public function assign(AssignJobOrderRequest $request, JobOrder $jobOrder): RedirectResponse
    {
        if ($jobOrder->status !== 'approved') {
            return redirect()->route('supervisor.AJO', $jobOrder)->with('error', 'Only approved job orders can be assigned.');
        }

        $staff = Staff::query()->with('user')->findOrFail($request->input('staff_id'));

        JobOrderAssignment::create([
            'job_order_id' => $jobOrder->job_order_id,
            'staff_id' => $staff->staff_id,
            'assigned_by' => auth()->id(),
            'assigned_date' => now()->toDateString(),
            'remarks' => $request->input('remarks'),
        ]);

        $jobOrder->update([
            'status' => 'in_progress',
            'remarks' => $request->filled('remarks') ? $request->input('remarks') : $jobOrder->remarks,
        ]);

        return redirect()->route('supervisor.AJO', $jobOrder)->with('success', 'Mechanic assigned successfully.');
    }
}
