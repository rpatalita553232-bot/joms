<x-app-layout>
    <div class="min-h-screen bg-white text-gray-900">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <x-admin-sidebar />


        <!-- Main Content -->
        <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

            <!-- Header -->
            <div>
                <h1 class="text-2xl font-medium">
                    Admin dashboard
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ auth()->user()->name }}
                </p>
            </div>


            <!-- Statistics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Customers
                    </p>

                    <p class="text-2xl font-medium">
                        {{ $customerCount }}
                    </p>
                </div>


                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Vehicles
                    </p>

                    <p class="text-2xl font-medium">
                        {{ $vehicleCount }}
                    </p>
                </div>


                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">
                        Active staff
                    </p>

                    <p class="text-2xl font-medium">
                        {{ $staffCount }}
                    </p>
                </div>


                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-[13px] text-gray-500 mb-1.5">Services offered</p>
                    <p class="text-2xl font-medium">{{ $serviceCount }}</p>
                </div>

            </div>


            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">

                <a href="{{ route('admin.users.addcustomer') }}"
                   class="flex-1 min-w-[140px] flex items-center justify-center gap-2 border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition">
                    <i class="ti ti-user-plus text-base"></i>
                    Add customer
                </a>

                <a href="{{ route('admin.users.addvehicles') }}"
                   class="flex-1 min-w-[140px] flex items-center justify-center gap-2 border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition">
                    <i class="ti ti-car text-base"></i>
                    Add vehicle
                </a>

                <a href="{{ route('admin.users.create') }}"
                   class="flex-1 min-w-[140px] flex items-center justify-center gap-2 border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition">
                    <i class="ti ti-user-check text-base"></i>
                    Add staff
                </a>

                <a href="{{ route('admin.users.addservices') }}"
                   class="flex-1 min-w-[140px] flex items-center justify-center gap-2 border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition">
                    <i class="ti ti-tool text-base"></i>
                    Add services
                </a>

            </div>


            <!-- Recent Job Orders -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">

                <div class="flex items-center justify-between gap-4 mb-4">

                    <p class="font-medium">
                        Recent job orders
                    </p>

                    <a href="{{ route('admin.job-orders') }}" class="text-sm text-gray-600 hover:text-gray-900">View all</a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-100">

                                <th class="font-normal py-2 pr-2">
                                    JO ID
                                </th>

                                <th class="font-normal py-2 pr-2">
                                    Customer
                                </th>

                                <th class="font-normal py-2 pr-2">
                                    Vehicle
                                </th>

                                <th class="font-normal py-2 pr-2">
                                    Date
                                </th>

                                <th class="font-normal py-2">
                                    Status
                                </th>

                            </tr>
                        </thead>


                        <tbody>
                            @forelse ($recentJobOrders as $jobOrder)
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-2.5 pr-2"><a href="{{ route('job-orders.show', $jobOrder) }}" class="font-medium hover:underline">JO-{{ $jobOrder->job_order_id }}</a></td>
                                    <td class="py-2.5 pr-2">{{ trim(($jobOrder->customer?->first_name ?? '').' '.($jobOrder->customer?->middle_name ?? '').' '.($jobOrder->customer?->last_name ?? '')) ?: 'Unknown customer' }}</td>
                                    <td class="py-2.5 pr-2">{{ $jobOrder->vehicle?->plate_number ?? 'N/A' }}</td>
                                    <td class="py-2.5 pr-2">{{ $jobOrder->date_issued ? \Illuminate\Support\Facades\Date::parse($jobOrder->date_issued)->format('M d, Y') : 'N/A' }}</td>
                                    <td class="py-2.5">
                                        <span @class([
                                            'rounded-md px-2.5 py-1 text-xs',
                                            'bg-yellow-50 text-yellow-700' => $jobOrder->status === 'pending',
                                            'bg-green-50 text-green-700' => $jobOrder->status === 'approved',
                                            'bg-red-50 text-red-700' => $jobOrder->status === 'rejected',
                                            'bg-blue-50 text-blue-700' => $jobOrder->status === 'in_progress',
                                            'bg-gray-100 text-gray-700' => $jobOrder->status === 'completed',
                                        ])>{{ ucfirst(str_replace('_', ' ', $jobOrder->status)) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-sm text-gray-500">No job orders have been created yet.</td></tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>


</x-app-layout>
