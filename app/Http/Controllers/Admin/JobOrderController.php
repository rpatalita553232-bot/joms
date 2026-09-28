<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOrder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'job_order_id' => ['nullable', 'integer', 'min:1'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'plate_number' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'integer', 'exists:services,service_id'],
            'status' => ['nullable', 'in:pending,approved,rejected,in_progress,completed'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $query = JobOrder::query()
            ->with(['customer', 'vehicle', 'services', 'jobOrderAssignments.staff.user'])
            ->when($filters['job_order_id'] ?? null, fn ($query, $jobOrderId) => $query->where('job_order_id', $jobOrderId))
            ->when($filters['customer_name'] ?? null, function ($query, $customerName) {
                $nameParts = preg_split('/\s+/', trim($customerName), -1, PREG_SPLIT_NO_EMPTY);

                $query->whereHas('customer', function ($customerQuery) use ($nameParts) {
                    foreach ($nameParts as $namePart) {
                        $customerQuery->where(function ($nameQuery) use ($namePart) {
                            $nameQuery->where('first_name', 'like', "%{$namePart}%")
                                ->orWhere('middle_name', 'like', "%{$namePart}%")
                                ->orWhere('last_name', 'like', "%{$namePart}%");
                        });
                    }
                });
            })
            ->when($filters['plate_number'] ?? null, function ($query, $plateNumber) {
                $query->whereHas('vehicle', fn ($vehicleQuery) => $vehicleQuery->where('plate_number', 'like', "%{$plateNumber}%"));
            })
            ->when($filters['service'] ?? null, function ($query, $serviceId) {
                $query->whereHas('services', fn ($serviceQuery) => $serviceQuery->where('services.service_id', $serviceId));
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) => $query->whereDate('date_issued', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) => $query->whereDate('date_issued', '<=', $dateTo))
            ->orderByDesc('date_issued')
            ->orderByDesc('job_order_id');

        $jobOrders = $query->paginate(15)->withQueryString();
        $services = Service::query()->orderBy('job_desc')->get(['service_id', 'job_desc']);

        return view('admin.job-orders', compact('jobOrders', 'services'));
    }
}