<x-app-layout>
    <div class="min-h-screen bg-white text-gray-900">

        
    <div class="flex min-h-screen">

        <x-supervisor-sidebar />


        <!-- Main -->
        <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

            <!-- Header -->
            <div>

                <h1 class="text-2xl font-medium">
                    Supervisor dashboard
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ auth()->user()->name }}
                </p>

            </div>


            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">Pending approval</p>
                    <p class="text-2xl font-medium">{{ $pendingCount }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">In progress</p>
                    <p class="text-2xl font-medium">{{ $inProgressCount }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">Completed this month</p>
                    <p class="text-2xl font-medium">{{ $completedThisMonthCount }}</p>
                </div>
            </div>


            <!-- Pending Approvals -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">

                <p class="font-medium mb-4">
                    Pending approvals
                </p>


                <div class="flex flex-col gap-2">
                    @forelse ($pendingJobOrders as $jobOrder)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium">JO-{{ $jobOrder->job_order_id }} · {{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} · {{ $jobOrder->services->first()?->job_desc ?? 'Service not set' }} · Submitted by {{ $jobOrder->creator?->name ?? 'Unknown user' }}</p>
                            </div>
                            <a href="{{ route('supervisor.AJO', ['search' => $jobOrder->job_order_id]) }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="ti ti-eye"></i> Review</a>
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-gray-200 p-5 text-center text-sm text-gray-500">No job orders are awaiting review.</p>
                    @endforelse
                </div>

            </div>


            <!-- Approval History -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">

                <p class="font-medium mb-4">
                    Approval history
                </p>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="text-left text-gray-500 border-b border-gray-100">

                                <th class="font-normal py-2 pr-2">
                                    JO ID
                                </th>

                                <th class="font-normal py-2 pr-2">
                                    Action
                                </th>

                                <th class="font-normal py-2 pr-2">
                                    Remarks
                                </th>

                                <th class="font-normal py-2">
                                    Date
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($recentApprovals as $approval)
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-2.5 pr-2"><a href="{{ route('supervisor.AJO', ['search' => $approval->job_order_id]) }}" class="hover:underline">JO-{{ $approval->job_order_id }}</a></td>
                                    <td class="py-2.5 pr-2">
                                        <span @class([
                                            'rounded-md px-2.5 py-1 text-xs',
                                            'bg-red-50 text-red-700' => $approval->status === 'rejected',
                                            'bg-green-50 text-green-700' => $approval->status === 'approved',
                                        ])>{{ ucfirst($approval->status) }}</span>
                                    </td>
                                    <td class="py-2.5 pr-2 text-gray-500">{{ $approval->remarks ?: '—' }}</td>
                                    <td class="py-2.5">{{ $approval->action_date ? \Illuminate\Support\Facades\Date::parse($approval->action_date)->format('M d, g:i a') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 text-center text-sm text-gray-500">No approval history yet.</td></tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>


</x-app-layout>
