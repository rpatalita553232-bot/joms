<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobOrderRequest;
use App\Http\Requests\UpdateJobOrderRequest;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\JobOrderAssignment;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOrderController extends Controller
{
    public function create(): View
    {
        $customers = Customer::query()->orderBy('first_name')->get();
        $vehicles = Vehicle::query()->with('customer')->orderBy('plate_number')->get();
        $services = Service::query()->orderBy('job_desc')->get();

        return view('mechanic.CJO', compact('customers', 'vehicles', 'services'));
    }

    public function store(StoreJobOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $service = Service::findOrFail($validated['service_id']);

        $jobOrder = JobOrder::create([
            'cust_id' => $validated['cust_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'created_by' => auth()->id(),
            'problem_description' => $validated['problem_description'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'pending',
            'date_issued' => now()->toDateString(),
            'expected_cmpl_date' => $validated['expected_cmpl_date'] ?? now()->addDays(3)->toDateString(),
            'total_cost' => $service->price,
        ]);

        $jobOrder->services()->syncWithoutDetaching([$service->service_id]);

        $staff = Staff::query()->where('user_id', auth()->id())->first();

        if ($staff) {
            JobOrderAssignment::create([
                'job_order_id' => $jobOrder->job_order_id,
                'staff_id' => $staff->staff_id,
                'assigned_by' => auth()->id(),
                'assigned_date' => now()->toDateString(),
                'remarks' => 'Auto-assigned to mechanic',
            ]);
        }

        return redirect()->route('job-orders.show', $jobOrder)->with('success', 'Job order created successfully.');
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,approved,rejected,in_progress,completed'],
        ]);
        $userId = auth()->id();

        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'jobOrderAssignments.staff.user'])
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhereHas('jobOrderAssignments.staff.user', function ($staffQuery) use ($userId) {
                        $staffQuery->where('users.id', $userId);
                    });
            })
            ->orderByDesc('job_order_id');

        if ($filters['status'] ?? null) {
            $query->where('status', $filters['status']);
        }

        if ($filters['search'] ?? null) {
            $search = trim($filters['search']);
            $query->where(function ($query) use ($search) {
                $query->where('problem_description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where('make', 'like', "%{$search}%")
                            ->orWhere('plate_number', 'like', "%{$search}%")
                            ->orWhere('engine_model', 'like', "%{$search}%");
                    });
            });
        }

        $jobOrders = $query->get();

        return view('mechanic.MJO', compact('jobOrders'));
    }

    public function needsRevision(Request $request): View
    {
        $userId = auth()->id();

        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'jobOrderAssignments.staff.user', 'jobOrderApprovals'])
            ->where('created_by', $userId)
            ->where(function ($query) {
                $query->where('status', 'rejected')
                    ->orWhereHas('jobOrderApprovals', function ($approvalQuery) {
                        $approvalQuery->where('status', 'rejected');
                    });
            })
            ->orderByDesc('job_order_id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($query) use ($search) {
                $query->where('problem_description', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $jobOrders = $query->get();

        return view('mechanic.needs-revision', compact('jobOrders'));
    }

    public function show(JobOrder $jobOrder): View
    {
        $jobOrder->load(['customer', 'vehicle', 'services', 'creator', 'jobOrderAssignments.staff.user', 'jobOrderApprovals.approvedBy']);

        return view('job-orders.show', compact('jobOrder'));
    }

    public function update(UpdateJobOrderRequest $request, JobOrder $jobOrder): RedirectResponse
    {
        $validated = $request->validated();

        $jobOrder->update([
            'problem_description' => $validated['problem_description'],
            'remarks' => $validated['remarks'] ?? null,
            'expected_cmpl_date' => $validated['expected_cmpl_date'],
            'status' => 'pending',
        ]);

        if ($request->filled('service_id')) {
            $jobOrder->services()->syncWithoutDetaching([$validated['service_id']]);
            $service = Service::findOrFail($validated['service_id']);
            $jobOrder->update(['total_cost' => $service->price]);
        }

        return redirect()->route('job-orders.show', $jobOrder)->with('success', 'Job order updated successfully.');
    }
}
