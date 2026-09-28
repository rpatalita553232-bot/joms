<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            <x-supervisor-sidebar />


            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                <!-- Header -->
                <div>

                    <h1 class="text-2xl font-medium">
                        Approval history
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Review previous approval and rejection actions
                    </p>

                </div>


                <form method="GET" action="{{ route('supervisor.approval-history') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">

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

                                By J.O. ID
                            </button>

                        </div>

                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                    <a href="{{ route('supervisor.approval-history') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                </form>


                <!-- History -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="font-medium">
                            Approval records
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            History of supervisor decisions on job orders
                        </p>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead>

                                <tr class="text-left text-gray-500 border-b border-gray-100">

                                    <th class="font-normal px-5 py-3">
                                        JO ID
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Customer
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Action
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Remarks
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody>
                                @forelse ($approvals as $approval)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-5 py-3 font-medium"><a href="{{ route('job-orders.show', $approval->jobOrder) }}" class="hover:underline">JO-{{ $approval->job_order_id }}</a></td>
                                        <td class="px-5 py-3">{{ trim(($approval->jobOrder?->customer?->first_name ?? '').' '.($approval->jobOrder?->customer?->middle_name ?? '').' '.($approval->jobOrder?->customer?->last_name ?? '')) ?: 'Unknown customer' }}</td>
                                        <td class="px-5 py-3">
                                            <span @class([
                                                'rounded-md px-2.5 py-1 text-xs',
                                                'bg-red-50 text-red-700' => $approval->status === 'rejected',
                                                'bg-green-50 text-green-700' => $approval->status === 'approved',
                                            ])>{{ ucfirst($approval->status) }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-gray-500">{{ $approval->remarks ?: '—' }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $approval->action_date ? \Illuminate\Support\Facades\Date::parse($approval->action_date)->format('M d, Y g:i a') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">No approval records match this search.</td></tr>
                                @endforelse
                            </tbody>

                            <tbody class="hidden">


                                <tr class="border-b border-gray-100 hover:bg-gray-50">

                                    <td class="px-5 py-3 font-medium">
                                        JO-1039
                                    </td>

                                    <td class="px-5 py-3">
                                        Liza Cruz
                                    </td>

                                    <td class="px-5 py-3">

                                        <span class="bg-red-50 text-red-700 text-xs px-2.5 py-1 rounded-md">
                                            Rejected
                                        </span>

                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Missing problem details
                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Sep 9, 3:14 pm
                                    </td>

                                </tr>


                                <tr class="border-b border-gray-100 hover:bg-gray-50">

                                    <td class="px-5 py-3 font-medium">
                                        JO-1038
                                    </td>

                                    <td class="px-5 py-3">
                                        Juan Dela Cruz
                                    </td>

                                    <td class="px-5 py-3">

                                        <span class="bg-green-50 text-green-700 text-xs px-2.5 py-1 rounded-md">
                                            Approved
                                        </span>

                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        &mdash;
                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Sep 9, 11:02 am
                                    </td>

                                </tr>


                                <tr class="border-b border-gray-100 hover:bg-gray-50">

                                    <td class="px-5 py-3 font-medium">
                                        JO-1037
                                    </td>

                                    <td class="px-5 py-3">
                                        Ana Reyes
                                    </td>

                                    <td class="px-5 py-3">

                                        <span class="bg-green-50 text-green-700 text-xs px-2.5 py-1 rounded-md">
                                            Approved
                                        </span>

                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        &mdash;
                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Sep 8, 2:26 pm
                                    </td>

                                </tr>


                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-3 font-medium">
                                        JO-1036
                                    </td>

                                    <td class="px-5 py-3">
                                        Mark Santos
                                    </td>

                                    <td class="px-5 py-3">

                                        <span class="bg-red-50 text-red-700 text-xs px-2.5 py-1 rounded-md">
                                            Rejected
                                        </span>

                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Please provide service details
                                    </td>

                                    <td class="px-5 py-3 text-gray-500">
                                        Sep 8, 10:18 am
                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>