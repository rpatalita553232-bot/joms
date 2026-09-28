<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Supervisor Sidebar --}}
            <x-supervisor-sidebar />


            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">


                {{-- Header --}}
                <div>

                    <h1 class="text-2xl font-medium">
                        Assign mechanic
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Assign approved job orders to available mechanics
                    </p>

                </div>

                @if(session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif


                {{-- Search + Filter --}}
                <form method="GET" action="{{ route('supervisor.assign-mechanic') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">

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

                            <p
                                class="px-3 py-2 text-xs font-medium
                                       uppercase tracking-wide text-gray-400"
                            >
                                Sort / Filter
                            </p>


                            {{-- Recent --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                Recent

                            </button>


                            {{-- Oldest --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-sort-ascending text-base text-gray-400"></i>

                                Oldest

                            </button>


                            <div class="my-1 border-t border-gray-100"></div>


                            {{-- By Customer --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-user text-base text-gray-400"></i>

                                By Customer

                            </button>


                            {{-- By Vehicle --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-car text-base text-gray-400"></i>

                                By Vehicle

                            </button>


                            {{-- By Mechanic --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-user-check text-base text-gray-400"></i>

                                By Mechanic

                            </button>


                            {{-- By J.O. ID --}}
                            <button
                                type="button"
                                @click="open = false"
                                class="flex w-full items-center gap-3
                                       rounded-lg px-3 py-2 text-sm text-gray-700
                                       transition hover:bg-gray-50"
                            >

                                <i class="ti ti-hash text-base text-gray-400"></i>

                                By J.O. ID

                            </button>

                        </div>

                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                    <a href="{{ route('supervisor.assign-mechanic') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                </form>


                {{-- Available Job Orders --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    {{-- Section Header --}}
                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="font-medium">
                            Job orders awaiting assignment
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Select a mechanic for each approved job order.
                        </p>

                    </div>


                    {{-- Job Orders --}}
                    <div class="p-4 flex flex-col gap-3">
                        @forelse ($jobOrders as $jobOrder)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('job-orders.show', $jobOrder) }}" class="text-sm font-medium hover:underline">JO-{{ $jobOrder->job_order_id }}</a>
                                            <span class="rounded-md bg-green-50 px-2 py-1 text-xs text-green-700">Approved</span>
                                        </div>
                                        <p class="mt-1 text-sm">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $jobOrder->vehicle?->make ?? 'Unknown vehicle' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} · {{ $jobOrder->services->first()?->job_desc ?? 'Service not set' }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('supervisor.job-orders.assign', $jobOrder) }}" class="flex w-full flex-col gap-2 sm:flex-row sm:items-end lg:w-auto">
                                        @csrf
                                        <div class="w-full sm:w-64">
                                            <label for="staff_id_{{ $jobOrder->job_order_id }}" class="mb-1 block text-xs font-medium text-gray-600">Mechanic</label>
                                            <select id="staff_id_{{ $jobOrder->job_order_id }}" name="staff_id" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                                <option value="">Select mechanic</option>
                                                @foreach ($mechanics as $mechanic)
                                                    <option value="{{ $mechanic->staff_id }}">{{ trim($mechanic->staff_first.' '.($mechanic->staff_middle ?? '').' '.$mechanic->staff_last) }} · {{ $mechanic->active_job_orders_count }} active</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('staff_id')" class="mt-1" />
                                        </div>
                                        <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-user-check"></i> Assign</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">No approved job orders are waiting for assignment.</div>
                        @endforelse
                    </div>

                    <div class="hidden p-4 flex flex-col gap-3">


                        {{-- ========================================= --}}
                        {{-- JO-1046 --}}
                        {{-- ========================================= --}}

                        <div
                            class="border border-gray-200 rounded-xl p-4"
                            x-data="{ open: false, selected: [] }"
                        >

                            <div class="flex flex-col lg:flex-row lg:items-start gap-4">


                                {{-- Job Order Information --}}
                                <div class="flex-1">

                                    <div class="flex items-center gap-2">

                                        <p class="font-medium text-sm">
                                            JO-1046
                                        </p>

                                        <span
                                            class="bg-green-50 text-green-700
                                                   text-xs px-2 py-1 rounded-md"
                                        >
                                            Approved
                                        </span>

                                    </div>


                                    <p class="text-sm mt-1">
                                        Juan Dela Cruz
                                    </p>


                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Toyota Vios · Brake repair
                                    </p>


                                    <p class="text-xs text-gray-400 mt-1">
                                        Approved Sep 12, 2026
                                    </p>

                                </div>


                                {{-- Assignment Controls --}}
                                <div
                                    class="flex flex-col sm:flex-row
                                           sm:items-start gap-2"
                                >

                                    {{-- Mechanic Selector --}}
                                    <div class="w-full sm:w-64">

                                        {{-- Selector Button --}}
                                        <button
                                            type="button"
                                            @click="open = !open"
                                            class="w-full inline-flex items-center
                                                   justify-between gap-3 rounded-lg
                                                   border border-gray-200
                                                   bg-white px-3 py-2 text-sm
                                                   text-gray-600
                                                   hover:bg-gray-50"
                                        >

                                            <span
                                                x-text="
                                                    selected.length === 0
                                                        ? 'Select mechanic(s)'
                                                        : selected.length + ' mechanic(s) selected'
                                                "
                                            >
                                                Select mechanic(s)
                                            </span>


                                            <i
                                                class="ti ti-chevron-down text-sm
                                                       transition-transform"
                                                :class="{ 'rotate-180': open }"
                                            ></i>

                                        </button>


                                        {{-- Mechanic Options --}}
                                        <div
                                            x-show="open"
                                            x-transition
                                            class="mt-2 w-full rounded-xl
                                                   border border-gray-200
                                                   bg-white p-2 shadow-sm"
                                        >

                                            <p
                                                class="px-3 py-2 text-xs
                                                       font-medium uppercase
                                                       tracking-wide text-gray-400"
                                            >
                                                Select mechanics
                                            </p>


                                            {{-- John Cuyos --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="john-cuyos"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    John Cuyos
                                                </span>

                                            </label>


                                            {{-- Sharwen Lopez --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="sharwen-lopez"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Sharwen Lopez
                                                </span>

                                            </label>


                                            {{-- Adili --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="adili"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Adili
                                                </span>

                                            </label>


                                            {{-- Mechanic 4 --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="mechanic-4"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Mechanic 4
                                                </span>

                                            </label>

                                        </div>


                                        {{-- Selected Mechanics --}}
                                        <template
                                            x-for="mechanic in selected"
                                            :key="mechanic"
                                        >

                                            <input
                                                type="hidden"
                                                name="mechanics[]"
                                                :value="mechanic"
                                            >

                                        </template>

                                    </div>


                                    {{-- Assign --}}
                                    <button
                                        type="button"
                                        class="inline-flex items-center
                                               justify-center gap-2
                                               rounded-lg bg-gray-900
                                               text-white px-4 py-2
                                               text-sm hover:bg-gray-800"
                                    >

                                        <i class="ti ti-user-check"></i>

                                        Assign

                                    </button>

                                </div>

                            </div>

                        </div>



                        {{-- ========================================= --}}
                        {{-- JO-1043 --}}
                        {{-- ========================================= --}}

                        <div
                            class="border border-gray-200 rounded-xl p-4"
                            x-data="{ open: false, selected: [] }"
                        >

                            <div class="flex flex-col lg:flex-row lg:items-start gap-4">


                                {{-- Job Order Information --}}
                                <div class="flex-1">

                                    <div class="flex items-center gap-2">

                                        <p class="font-medium text-sm">
                                            JO-1043
                                        </p>

                                        <span
                                            class="bg-green-50 text-green-700
                                                   text-xs px-2 py-1 rounded-md"
                                        >
                                            Approved
                                        </span>

                                    </div>


                                    <p class="text-sm mt-1">
                                        Mark Santos
                                    </p>


                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Ford Ranger · Engine check
                                    </p>


                                    <p class="text-xs text-gray-400 mt-1">
                                        Approved Sep 11, 2026
                                    </p>

                                </div>


                                {{-- Assignment Controls --}}
                                <div
                                    class="flex flex-col sm:flex-row
                                           sm:items-start gap-2"
                                >

                                    {{-- Mechanic Selector --}}
                                    <div class="w-full sm:w-64">

                                        {{-- Selector Button --}}
                                        <button
                                            type="button"
                                            @click="open = !open"
                                            class="w-full inline-flex items-center
                                                   justify-between gap-3 rounded-lg
                                                   border border-gray-200
                                                   bg-white px-3 py-2 text-sm
                                                   text-gray-600
                                                   hover:bg-gray-50"
                                        >

                                            <span
                                                x-text="
                                                    selected.length === 0
                                                        ? 'Select mechanic(s)'
                                                        : selected.length + ' mechanic(s) selected'
                                                "
                                            >
                                                Select mechanic(s)
                                            </span>


                                            <i
                                                class="ti ti-chevron-down text-sm
                                                       transition-transform"
                                                :class="{ 'rotate-180': open }"
                                            ></i>

                                        </button>


                                        {{-- Mechanic Options --}}
                                        <div
                                            x-show="open"
                                            x-transition
                                            class="mt-2 w-full rounded-xl
                                                   border border-gray-200
                                                   bg-white p-2 shadow-sm"
                                        >

                                            <p
                                                class="px-3 py-2 text-xs
                                                       font-medium uppercase
                                                       tracking-wide text-gray-400"
                                            >
                                                Select mechanics
                                            </p>


                                            {{-- John Cuyos --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="john-cuyos"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    John Cuyos
                                                </span>

                                            </label>


                                            {{-- Sharwen Lopez --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="sharwen-lopez"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Sharwen Lopez
                                                </span>

                                            </label>


                                            {{-- Adili --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="adili"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Adili
                                                </span>

                                            </label>


                                            {{-- Mechanic 4 --}}
                                            <label
                                                class="flex cursor-pointer
                                                       items-center gap-3
                                                       rounded-lg px-3 py-2
                                                       hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    value="mechanic-4"
                                                    x-model="selected"
                                                    class="rounded border-gray-300
                                                           text-gray-900
                                                           focus:ring-gray-200"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    Mechanic 4
                                                </span>

                                            </label>

                                        </div>


                                        {{-- Selected Mechanics --}}
                                        <template
                                            x-for="mechanic in selected"
                                            :key="mechanic"
                                        >

                                            <input
                                                type="hidden"
                                                name="mechanics[]"
                                                :value="mechanic"
                                            >

                                        </template>

                                    </div>


                                    {{-- Assign --}}
                                    <button
                                        type="button"
                                        class="inline-flex items-center
                                               justify-center gap-2
                                               rounded-lg bg-gray-900
                                               text-white px-4 py-2
                                               text-sm hover:bg-gray-800"
                                    >

                                        <i class="ti ti-user-check"></i>

                                        Assign

                                    </button>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>



                {{-- Active Mechanics --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">

                    <p class="font-medium">
                        Active mechanics
                    </p>

                    <p class="text-xs text-gray-500 mt-1 mb-4">
                        Current mechanic workload
                    </p>


                    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @forelse ($mechanics as $mechanic)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">{{ trim($mechanic->staff_first.' '.($mechanic->staff_middle ?? '').' '.$mechanic->staff_last) }}</p>
                                    <span class="h-2 w-2 rounded-full {{ $mechanic->active_job_orders_count > 0 ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ $mechanic->active_job_orders_count }} active job order{{ $mechanic->active_job_orders_count === 1 ? '' : 's' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No mechanics are available.</p>
                        @endforelse
                    </div>

                    <div class="hidden grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">


                        {{-- Adili --}}
                        <div class="border border-gray-200 rounded-lg p-4">

                            <div class="flex items-center justify-between">

                                <p class="font-medium text-sm">
                                    Adili
                                </p>

                                <span
                                    class="h-2 w-2 rounded-full bg-green-500"
                                ></span>

                            </div>

                            <p class="text-xs text-gray-500 mt-1">
                                2 active job orders
                            </p>

                        </div>


                        {{-- John Cuyos --}}
                        <div class="border border-gray-200 rounded-lg p-4">

                            <div class="flex items-center justify-between">

                                <p class="font-medium text-sm">
                                    John Cuyos
                                </p>

                                <span
                                    class="h-2 w-2 rounded-full bg-green-500"
                                ></span>

                            </div>

                            <p class="text-xs text-gray-500 mt-1">
                                1 active job order
                            </p>

                        </div>


                        {{-- Sharwen Lopez --}}
                        <div class="border border-gray-200 rounded-lg p-4">

                            <div class="flex items-center justify-between">

                                <p class="font-medium text-sm">
                                    Sharwen Lopez
                                </p>

                                <span
                                    class="h-2 w-2 rounded-full bg-green-500"
                                ></span>

                            </div>

                            <p class="text-xs text-gray-500 mt-1">
                                1 active job order
                            </p>

                        </div>


                        {{-- Mechanic 4 --}}
                        <div class="border border-gray-200 rounded-lg p-4">

                            <div class="flex items-center justify-between">

                                <p class="font-medium text-sm">
                                    Mechanic 4
                                </p>

                                <span
                                    class="h-2 w-2 rounded-full bg-gray-500"
                                ></span>

                            </div>

                            <p class="text-xs text-gray-500 mt-1">
                                Available
                            </p>

                        </div>


                    </div>

                </div>


            </main>

        </div>

    </div>

</x-app-layout>