<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            <x-supervisor-sidebar />


            <!-- Main -->
            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                <!-- Header -->
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-medium">
                            Pending approvals
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Review job orders submitted for approval
                        </p>
                    </div>

                    <div class="flex items-center gap-2 bg-amber-50 text-amber-700 rounded-lg px-3 py-2 text-sm">
                        <i class="ti ti-clock"></i>
                        {{ $jobOrders->count() }} pending
                    </div>

                </div>


                <!-- Search + Filter -->
                <form method="GET" action="{{ route('supervisor.pending-approvals') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">

                    {{-- Search --}}
                    <div class="relative max-w-sm flex-1 sm:max-w-sm">

                        <i
                            class="ti ti-search absolute left-3 top-1/2
                                -translate-y-1/2 text-gray-400"
                        ></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search Job Orders..."
                            class="w-full rounded-lg border border-gray-200
                                py-2 pl-9 pr-3 text-sm
                                focus:border-gray-300
                                focus:outline-none
                                focus:ring-2 focus:ring-gray-100"
                        >

                    </div>


                    {{-- Filter --}}
                    <div class="hidden relative" x-data="{ open: false }">

                        <button
                            type="button"
                            @click="open = !open"
                            @click.outside="open = false"
                            class="inline-flex w-full items-center justify-center
                                gap-2 rounded-lg border border-gray-200
                                bg-white px-3 py-2 text-sm text-gray-600
                                transition hover:bg-gray-50
                                sm:w-auto"
                        >

                            <i class="ti ti-filter text-base"></i>

                            Filter

                            <i
                                class="ti ti-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"
                            ></i>

                        </button>


                        {{-- Filter Dropdown --}}
                        <div
                            x-show="open"
                            x-transition
                            class="absolute right-0 z-20 mt-2 w-48
                                rounded-xl border border-gray-200
                                bg-white p-2 shadow-lg"
                        >

                            <p class="px-3 py-2 text-xs font-medium uppercase tracking-wide text-gray-400">
                                Sort / Filter
                            </p>


                             {{-- Low-High --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                Recent
                            </button>

                             {{-- High-Low --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                Oldest
                            </button>


                            <div class="my-1 border-t border-gray-100"></div>


                            {{-- By Name --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-user text-base text-gray-400"></i>

                                By Customer
                            </button>

                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-user text-base text-gray-400"></i>

                                By Vehicle
                            </button>

                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-user text-base text-gray-400"></i>

                                By Mechanic
                            </button>

                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-user text-base text-gray-400"></i>

                                By J.O. ID
                            </button>

                        </div>

                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                    <a href="{{ route('supervisor.pending-approvals') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                </form>


                <!-- Pending List -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="font-medium">
                            Job orders awaiting approval
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Review the details before approving or rejecting.
                        </p>

                    </div>


                    <div class="p-4 flex flex-col gap-3">


                        @forelse ($jobOrders as $jobOrder)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium">JO-{{ $jobOrder->job_order_id }}</p>
                                            <span class="rounded-md bg-amber-50 px-2 py-1 text-xs text-amber-700">Pending</span>
                                        </div>
                                        <p class="mt-1 text-sm">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} · {{ $jobOrder->services->first()?->job_desc ?? 'Service not set' }}</p>
                                        <p class="mt-1 text-xs text-gray-400">Submitted by {{ $jobOrder->creator?->name ?? 'Unknown user' }} · {{ $jobOrder->date_issued ? \Illuminate\Support\Facades\Date::parse($jobOrder->date_issued)->format('M d, Y') : 'N/A' }}</p>
                                    </div>

                                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                                        <a href="{{ route('job-orders.show', $jobOrder) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="ti ti-eye"></i> Review</a>
                                        <form method="POST" action="{{ route('supervisor.job-orders.approve', $jobOrder) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-700 hover:bg-green-100"><i class="ti ti-check"></i> Approve</button>
                                        </form>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('supervisor.job-orders.reject', $jobOrder) }}" class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-end">
                                    @csrf
                                    <div class="min-w-0 flex-1">
                                        <label for="remarks_{{ $jobOrder->job_order_id }}" class="mb-1 block text-xs font-medium text-gray-600">Rejection remarks</label>
                                        <input id="remarks_{{ $jobOrder->job_order_id }}" name="remarks" type="text" value="{{ old('remarks') }}" required maxlength="1000" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                        <x-input-error :messages="$errors->get('remarks')" class="mt-1" />
                                    </div>
                                    <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700 hover:bg-red-100"><i class="ti ti-x"></i> Reject</button>
                                </form>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">No job orders are awaiting approval.</div>
                        @endforelse

                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>