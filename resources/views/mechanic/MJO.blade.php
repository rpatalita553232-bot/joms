<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Mechanic Sidebar --}}
            <x-mechanic-sidebar />


            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                {{-- Header --}}
                <div>
                    <h1 class="text-2xl font-medium">
                        My job orders
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        View and manage job orders assigned to you
                    </p>
                </div>


                {{-- Search and Filter --}}
                <form method="GET" action="{{ route('mechanic.MJO') }}" class="flex flex-wrap gap-3">

                    <div class="relative flex-1 min-w-[220px] max-w-md">

                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search job orders..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-blue-100
                                   focus:border-blue-300"
                        >

                    </div>


                    <select
                        name="status"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-600"
                    >
                        <option value="">All statuses</option>
                        @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Needs revision', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                    <a href="{{ route('mechanic.MJO') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                </form>


                {{-- Job Orders --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="font-medium">
                            Assigned job orders
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Job orders currently assigned to you
                        </p>

                    </div>


                    <div class="p-4 flex flex-col gap-3">
                        @forelse ($jobOrders as $jobOrder)
                            <div class="rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium">JO-{{ $jobOrder->job_order_id }}</p>
                                            <span @class([
                                                'rounded-md px-2.5 py-1 text-xs',
                                                'bg-yellow-50 text-yellow-700' => $jobOrder->status === 'pending',
                                                'bg-green-50 text-green-700' => $jobOrder->status === 'approved',
                                                'bg-red-50 text-red-700' => $jobOrder->status === 'rejected',
                                                'bg-blue-50 text-blue-700' => $jobOrder->status === 'in_progress',
                                                'bg-gray-100 text-gray-700' => $jobOrder->status === 'completed',
                                            ])>{{ ucfirst(str_replace('_', ' ', $jobOrder->status)) }}</span>
                                        </div>
                                        <p class="mt-1 text-sm">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} · {{ $jobOrder->services->first()?->job_desc ?? 'Service not set' }}</p>
                                        <p class="mt-1 text-xs text-gray-400">Issued {{ $jobOrder->date_issued ? \Illuminate\Support\Facades\Date::parse($jobOrder->date_issued)->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('job-orders.show', $jobOrder) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-white">View details <i class="ti ti-chevron-right"></i></a>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">No job orders match this search.</p>
                        @endforelse
                    </div>

                    <div class="hidden p-4 flex flex-col gap-3">


                        {{-- JO 1042 --}}
                        <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-2">

                                        <p class="font-medium text-sm">
                                            JO-1042
                                        </p>

                                        <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-md">
                                            Pending
                                        </span>

                                    </div>

                                    <p class="text-sm mt-1">
                                        Juan Dela Cruz
                                    </p>

                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Toyota Vios · Brake repair
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Assigned Sep 10, 2026
                                    </p>

                                </div>


                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2
                                           rounded-lg border border-gray-200 px-3 py-2
                                           text-sm text-gray-700 hover:bg-white shrink-0"
                                >
                                    View details
                                    <i class="ti ti-chevron-right"></i>
                                </button>

                            </div>

                        </div>


                        {{-- JO 1037 --}}
                        <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-2">

                                        <p class="font-medium text-sm">
                                            JO-1037
                                        </p>

                                        <span class="bg-green-50 text-green-700 text-xs px-2.5 py-1 rounded-md">
                                            Approved
                                        </span>

                                    </div>

                                    <p class="text-sm mt-1">
                                        Liza Cruz
                                    </p>

                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Suzuki Ertiga · Suspension check
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Approved Sep 8, 2026
                                    </p>

                                </div>


                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2
                                           rounded-lg border border-gray-200 px-3 py-2
                                           text-sm text-gray-700 hover:bg-white shrink-0"
                                >
                                    View details
                                    <i class="ti ti-chevron-right"></i>
                                </button>

                            </div>

                        </div>


                        {{-- JO 1035 --}}
                        <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-2">

                                        <p class="font-medium text-sm">
                                            JO-1035
                                        </p>

                                        <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-md">
                                            Assigned to you
                                        </span>

                                    </div>

                                    <p class="text-sm mt-1">
                                        Ana Reyes
                                    </p>

                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Honda Civic · Oil change
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Assigned Sep 7, 2026
                                    </p>

                                </div>


                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2
                                           rounded-lg border border-gray-200 px-3 py-2
                                           text-sm text-gray-700 hover:bg-white shrink-0"
                                >
                                    View details
                                    <i class="ti ti-chevron-right"></i>
                                </button>

                            </div>

                        </div>


                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>