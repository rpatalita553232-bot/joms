<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Mechanic Sidebar --}}
            <x-mechanic-sidebar />


            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                {{-- Header --}}
                <div>

                <form method="GET" action="{{ route('mechanic.needs-revision') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <div class="relative max-w-sm flex-1">
                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search job orders..." class="w-full rounded-lg border border-gray-200 py-2 pl-9 pr-3 text-sm focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-100">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                    <a href="{{ route('mechanic.needs-revision') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                </form>

                    <h1 class="text-2xl font-medium">
                        Needs revision
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Review supervisor feedback and revise your job orders
                    </p>

                </div>


                {{-- Notice --}}
                <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">

                    <i class="ti ti-alert-circle text-red-600 text-xl mt-0.5"></i>

                    <div>

                        <p class="text-sm font-medium text-red-800">
                            Job orders requiring revision
                        </p>

                        <p class="text-xs text-red-700 mt-1">
                            Please review the supervisor's remarks before resubmitting.
                        </p>

                    </div>

                </div>


                <div class="flex flex-col gap-4">
                    @forelse ($jobOrders as $jobOrder)
                        @php($rejection = $jobOrder->jobOrderApprovals->where('status', 'rejected')->last())
                        <div class="overflow-hidden rounded-xl border border-red-200 bg-white">
                            <div class="border-b border-gray-100 p-5">
                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium">JO-{{ $jobOrder->job_order_id }}</p>
                                            <span class="rounded-md bg-red-50 px-2.5 py-1 text-xs text-red-700">Needs revision</span>
                                        </div>
                                        <p class="mt-1 text-sm">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} · {{ $jobOrder->services->first()?->job_desc ?? 'Service not set' }}</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Returned {{ $rejection?->action_date ? \Illuminate\Support\Facades\Date::parse($rejection->action_date)->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="mb-2 text-sm font-medium">Supervisor remarks</p>
                                <p class="rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $rejection?->remarks ?? $jobOrder->remarks ?? 'Review and update the job order details.' }}</p>
                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div class="rounded-lg bg-gray-50 p-3"><p class="text-xs text-gray-500">Customer</p><p class="mt-1 text-sm">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) }}</p></div>
                                    <div class="rounded-lg bg-gray-50 p-3"><p class="text-xs text-gray-500">Vehicle</p><p class="mt-1 text-sm">{{ $jobOrder->vehicle?->make ?? 'N/A' }} {{ $jobOrder->vehicle?->engine_model ?? '' }}</p></div>
                                    <div class="rounded-lg bg-gray-50 p-3"><p class="text-xs text-gray-500">Service</p><p class="mt-1 text-sm">{{ $jobOrder->services->first()?->job_desc ?? 'N/A' }}</p></div>
                                    <div class="rounded-lg bg-gray-50 p-3"><p class="text-xs text-gray-500">Submitted</p><p class="mt-1 text-sm">{{ $jobOrder->date_issued ? \Illuminate\Support\Facades\Date::parse($jobOrder->date_issued)->format('M d, Y') : 'N/A' }}</p></div>
                                </div>
                            </div>
                            <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-5 py-4">
                                <a href="{{ route('job-orders.show', $jobOrder) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-edit"></i> Revise job order</a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-red-200 bg-red-50 p-6 text-center text-sm text-gray-600">No job orders currently need revision.</div>
                    @endforelse
                </div>

                {{-- Original sample card kept hidden while the live revision cards use the same styling. --}}
                <div class="hidden bg-white border border-red-200 rounded-xl overflow-hidden">

                    <div class="p-5 border-b border-gray-100">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                            <div>

                                <div class="flex items-center gap-2">

                                    <p class="font-medium">
                                        JO-1039
                                    </p>

                                    <span class="bg-red-50 text-red-700 text-xs px-2.5 py-1 rounded-md">
                                        Needs revision
                                    </span>

                                </div>

                                <p class="text-sm mt-1">
                                    Liza Cruz
                                </p>

                                <p class="text-xs text-gray-500 mt-0.5">
                                    Suzuki Ertiga · Suspension check
                                </p>

                            </div>

                            <p class="text-xs text-gray-400">
                                Returned Sep 9, 2026
                            </p>

                        </div>

                    </div>


                    {{-- Supervisor Remarks --}}
                    <div class="p-5">

                        <p class="text-sm font-medium mb-2">
                            Supervisor remarks
                        </p>

                        <div class="rounded-lg bg-red-50 p-4">

                            <p class="text-sm text-red-700">
                                Missing problem details. Please provide a more complete
                                description of the customer's reported issue.
                            </p>

                        </div>

                    </div>


                    {{-- Current Details --}}
                    <div class="px-5 pb-5">

                        <p class="text-sm font-medium mb-3">
                            Current job order details
                        </p>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">


                            <div class="rounded-lg bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Customer
                                </p>

                                <p class="text-sm mt-1">
                                    Liza Cruz
                                </p>

                            </div>


                            <div class="rounded-lg bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Vehicle
                                </p>

                                <p class="text-sm mt-1">
                                    Suzuki Ertiga
                                </p>

                            </div>


                            <div class="rounded-lg bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Service
                                </p>

                                <p class="text-sm mt-1">
                                    Suspension check
                                </p>

                            </div>


                            <div class="rounded-lg bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Submitted
                                </p>

                                <p class="text-sm mt-1">
                                    Sep 8, 2026
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Action --}}
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">

                        <button
                            type="button"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg bg-gray-900 text-white px-4 py-2
                                   text-sm hover:bg-gray-800"
                        >
                            <i class="ti ti-edit"></i>
                            Revise job order
                        </button>

                    </div>

                </div>


                {{-- Empty-state example --}}
                <div class="bg-gray-50 rounded-xl p-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white">

                            <i class="ti ti-info-circle text-gray-400"></i>

                        </div>

                        <div>

                            <p class="text-sm font-medium">
                                Revision workflow
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                After making the requested changes, resubmit the job order
                                for supervisor approval.
                            </p>

                        </div>

                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>