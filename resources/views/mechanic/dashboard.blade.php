<x-app-layout>
    <div class="min-h-screen bg-white text-gray-900">


    <div class="flex min-h-screen">

        <x-mechanic-sidebar />


        <!-- Main -->
        <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

            <!-- Header -->
            <div class="flex items-start justify-between gap-4">

                <div>

                    <h1 class="text-2xl font-medium">
                        Mechanic dashboard
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Welcome back, {{ auth()->user()->name }}
                    </p>

                </div>


                <a href="{{ route('mechanic.CJO') }}"
                   class="shrink-0 flex items-center gap-2 bg-gray-900 text-white rounded-lg px-4 py-2 text-sm hover:bg-gray-800 transition">

                    <i class="ti ti-plus text-base"></i>
                    Create job order

                </a>

            </div>


            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Tasks
                    </p>

                    <p class="text-2xl font-medium">{{ $inProgressCount }}</p>

                </div>


                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Pending
                    </p>

                    <p class="text-2xl font-medium">{{ $pendingCount }}</p>

                </div>


                <div class="rounded-lg border border-red-200 bg-red-50 p-4">

                    <p class="mb-1.5 text-[13px] text-red-700">
                        Needs revision
                    </p>

                    <p class="text-2xl font-medium text-red-800">{{ $needsRevisionCount }}</p>

                </div>


                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Job orders created
                    </p>

                    <p class="text-2xl font-medium">{{ $createdJobOrderCount }}</p>

                </div>

            </div>


            <!-- Your Tasks -->
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-[15px] font-semibold text-gray-900">
                            Your Tasks
                        </h2>
                        <p class="text-[13px] text-gray-500 mt-0.5">
                            Tasks assigned to you
                        </p>
                    </div>

                    <a href="{{ route('mechanic.MJO') }}"
                        class="text-[13px] font-medium text-gray-700 hover:text-gray-900 transition">
                        View all
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/70">
                                <th class="px-5 py-3 text-[12px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Task
                                </th>
                                <th class="px-5 py-3 text-[12px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Job Order
                                </th>
                                <th class="px-5 py-3 text-[12px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Due Date
                                </th>
                                <th class="px-5 py-3 text-[12px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($assignedJobOrders as $jobOrder)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <p class="text-[13px] font-medium text-gray-900">{{ $jobOrder->services->first()?->job_desc ?? 'Job order' }}</p>
                                        <p class="text-[12px] text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-[13px] text-gray-700"><a href="{{ route('job-orders.show', $jobOrder) }}" class="hover:underline">JO-{{ $jobOrder->job_order_id }}</a></td>
                                    <td class="px-5 py-4 text-[13px] text-gray-600">{{ $jobOrder->expected_cmpl_date ? \Illuminate\Support\Facades\Date::parse($jobOrder->expected_cmpl_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-5 py-4"><span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">In progress</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500">No job orders are assigned to you yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- My Job Orders -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">

                <p class="font-medium mb-4">
                    My job orders
                </p>


                <div class="flex flex-col gap-2">
                    @forelse ($createdJobOrders as $jobOrder)
                        <a href="{{ route('job-orders.show', $jobOrder) }}" class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3 hover:bg-gray-50">
                            <div class="min-w-0">
                                <p class="text-sm font-medium">JO-{{ $jobOrder->job_order_id }} · {{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }}</p>
                            </div>
                            <span @class([
                                'shrink-0 rounded-md px-2.5 py-1 text-xs',
                                'bg-yellow-50 text-yellow-700' => $jobOrder->status === 'pending',
                                'bg-green-50 text-green-700' => $jobOrder->status === 'approved',
                                'bg-red-50 text-red-700' => $jobOrder->status === 'rejected',
                                'bg-blue-50 text-blue-700' => $jobOrder->status === 'in_progress',
                                'bg-gray-100 text-gray-700' => $jobOrder->status === 'completed',
                            ])>{{ ucfirst(str_replace('_', ' ', $jobOrder->status)) }}</span>
                        </a>
                    @empty
                        <p class="rounded-lg border border-dashed border-gray-200 p-5 text-center text-sm text-gray-500">You have not created any job orders yet.</p>
                    @endforelse
                </div>

            </div>


            <!-- Needs Revision -->
            <div class="bg-white border border-red-200 rounded-xl p-5">

                <p class="font-medium mb-4">
                    Needs revision
                </p>


                <div class="flex flex-col gap-2">
                    @forelse ($needsRevisionJobOrders as $jobOrder)
                        <div class="flex items-center justify-between gap-4 rounded-lg bg-red-50 px-4 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-red-700">JO-{{ $jobOrder->job_order_id }} · {{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                <p class="mt-0.5 text-xs text-red-700/80">{{ $jobOrder->remarks ?: 'Review and update this job order.' }}</p>
                            </div>
                            <a href="{{ route('job-orders.show', $jobOrder) }}" class="shrink-0 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-sm text-red-700 transition hover:bg-red-100"><i class="ti ti-refresh text-base"></i> Revise</a>
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-red-200 p-5 text-center text-sm text-gray-500">No job orders need revision.</p>
                    @endforelse
                </div>

            </div>

        </main>

    </div>

</div>


</x-app-layout>
