<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            <x-admin-sidebar />


            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                <!-- Header -->
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-medium">
                            Customers
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Manage customer records
                        </p>
                    </div>

                    <a href="{{ route('admin.users.addcustomer') }}"
                       class="inline-flex items-center gap-2 bg-gray-900 text-white rounded-lg px-4 py-2 text-sm hover:bg-gray-800 transition">

                        <i class="ti ti-user-plus"></i>
                        Add customer

                    </a>

                </div>


                <!-- Search + Filter -->
                <form method="GET" action="{{ route('admin.customers') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">

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
                            placeholder="Search customers..."
                            class="w-full rounded-lg border border-gray-200
                                py-2 pl-9 pr-3 text-sm
                                focus:border-gray-300
                                focus:outline-none
                                focus:ring-2 focus:ring-gray-100"
                        >

                    </div>


                    {{-- Filter --}}
                    <div
                        class="relative"
                        x-data="{ open: false }"
                    >

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


                            {{-- A-Z --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-ascending text-base text-gray-400"></i>

                                A–Z
                            </button>


                            {{-- Z-A --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                Z–A
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

                                By make
                            </button>


                            {{-- By Role --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-user-shield text-base text-gray-400"></i>

                                By Owner
                            </button>

                        </div>

                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                </form>


                <!-- Customer Table -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">
                        <p class="font-medium">
                            Customer records
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $customers->count() }} customers
                        </p>
                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead>
                                <tr class="text-left text-gray-500 border-b border-gray-100">

                                    <th class="font-normal px-5 py-3">
                                        Customer
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Contact
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Vehicles
                                    </th>

                                    <th class="font-normal px-5 py-3">
                                        Date added
                                    </th>

                                    <th class="font-normal px-5 py-3 text-right">
                                        Action
                                    </th>

                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-5 py-3">
                                            <p class="font-medium">{{ trim($customer->first_name.' '.($customer->middle_name ?? '').' '.$customer->last_name) }}</p>
                                            <p class="text-xs text-gray-500">Customer #{{ $customer->cust_id }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-gray-600">{{ $customer->contact_number }}</td>
                                        <td class="px-5 py-3 text-gray-600">{{ $customer->vehicles_count }} vehicle{{ $customer->vehicles_count === 1 ? '' : 's' }}</td>
                                        <td class="px-5 py-3 text-gray-600">{{ $customer->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"><i class="ti ti-edit text-sm"></i> Edit</a>
                                                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50 hover:border-red-200"><i class="ti ti-trash text-sm"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">No customers found.</td></tr>
                                @endforelse
                            </tbody>

                        </table>

                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>