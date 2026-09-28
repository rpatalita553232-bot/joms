<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Mechanic Sidebar --}}
            <x-mechanic-sidebar />


            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-8">

                {{-- Header --}}
                <div class="mb-6">

                    <h1 class="text-2xl font-medium">
                        Create job order
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Create a new job order for a customer vehicle
                    </p>

                </div>


                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ route('mechanic.job-orders.store') }}"
                    class="max-w-4xl flex flex-col gap-5"
                >

                    @csrf


                    {{-- Customer and Vehicle --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-5">

                        <div class="mb-5">

                            <p class="font-medium">
                                Customer and vehicle
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Select the customer and vehicle for this job order.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                            {{-- Customer --}}
                            <div>

                                <label
                                    for="cust_id"
                                    class="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Customer
                                </label>

                                <select
                                    id="cust_id"
                                    name="cust_id"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                           bg-white focus:outline-none focus:ring-2
                                           focus:ring-blue-100 focus:border-blue-300"
                                >

                                    <option value="">Select customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->cust_id }}" @selected((string) old('cust_id') === (string) $customer->cust_id)>
                                            {{ trim($customer->first_name.' '.($customer->middle_name ?? '').' '.$customer->last_name) }}
                                        </option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('cust_id')" class="mt-2" />

                            </div>


                            {{-- Vehicle --}}
                            <div>

                                <label
                                    for="vehicle_id"
                                    class="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Vehicle
                                </label>

                                <select
                                    id="vehicle_id"
                                    name="vehicle_id"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                           bg-white focus:outline-none focus:ring-2
                                           focus:ring-blue-100 focus:border-blue-300"
                                >

                                    <option value="">Select vehicle</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->vehicle_id }}" @selected((string) old('vehicle_id') === (string) $vehicle->vehicle_id)>
                                            {{ $vehicle->customer?->first_name }} {{ $vehicle->customer?->last_name }} · {{ $vehicle->plate_number }} · {{ $vehicle->make }} {{ $vehicle->engine_model }}
                                        </option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />

                            </div>

                        </div>

                    </div>


                    {{-- Service Details --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-5">

                        <div class="mb-5">

                            <p class="font-medium">
                                Service details
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Provide the service and problem details.
                            </p>

                        </div>


                        <div class="flex flex-col gap-4">


                            {{-- Service --}}
                            <div>

                                <label
                                    for="service_id"
                                    class="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Service
                                </label>

                                <select
                                    id="service_id"
                                    name="service_id"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                           bg-white focus:outline-none focus:ring-2
                                           focus:ring-blue-100 focus:border-blue-300"
                                >

                                    <option value="">Select service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->service_id }}" @selected((string) old('service_id') === (string) $service->service_id)>
                                            {{ $service->job_desc }} · ₱{{ number_format((float) $service->price, 2) }}
                                        </option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('service_id')" class="mt-2" />

                            </div>


                            {{-- Problem --}}
                            <div>

                                <label
                                    for="problem_description"
                                    class="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Problem description
                                </label>

                                <textarea
                                    id="problem_description"
                                    name="problem_description"
                                    rows="4"
                                    placeholder="Describe the vehicle problem..."
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                           resize-none focus:outline-none focus:ring-2
                                           focus:ring-blue-100 focus:border-blue-300"
                                >{{ old('problem_description') }}</textarea>
                                <x-input-error :messages="$errors->get('problem_description')" class="mt-2" />

                            </div>


                            {{-- Initial Findings --}}
                            <div>

                                <label
                                    for="remarks"
                                    class="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Initial findings
                                </label>

                                <textarea
                                    id="remarks"
                                    name="remarks"
                                    rows="4"
                                    placeholder="Enter any initial findings..."
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                           resize-none focus:outline-none focus:ring-2
                                           focus:ring-blue-100 focus:border-blue-300"
                                >{{ old('remarks') }}</textarea>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>

                            <div>
                                <label for="expected_cmpl_date" class="block text-sm font-medium text-gray-700 mb-1.5">Expected completion date</label>
                                <input id="expected_cmpl_date" name="expected_cmpl_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('expected_cmpl_date') }}" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300">
                                <x-input-error :messages="$errors->get('expected_cmpl_date')" class="mt-2" />
                            </div>

                            </div>
                        </div>

                    </div>


                    {{-- Submit --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">

                        <a
                            href="{{ url()->previous() }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   border border-gray-200 px-4 py-2.5 text-sm
                                   text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg bg-gray-900 text-white px-4 py-2.5
                                   text-sm hover:bg-gray-800"
                        >
                            <i class="ti ti-send"></i>
                            Submit job order
                        </button>

                    </div>

                </form>

            </main>

        </div>

    </div>

</x-app-layout>