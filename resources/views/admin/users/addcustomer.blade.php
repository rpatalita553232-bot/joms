<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Admin Sidebar --}}
            <x-admin-sidebar />


            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-6 sm:p-8">

                {{-- Header --}}
                <div class="mb-6">

                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-3">

                        <a
                            href="{{ route('admin.customers') }}"
                            class="hover:text-gray-700"
                        >
                            Customers
                        </a>

                        <i class="ti ti-chevron-right text-xs"></i>

                        <span class="text-gray-600">
                            {{ isset($customer) ? 'Edit customer' : 'Add customer' }}
                        </span>

                    </div>

                    <h1 class="text-2xl font-medium">
                        Add customer
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Add a new customer to the system.
                    </p>

                </div>


                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ isset($customer) ? route('admin.customers.update', $customer) : route('admin.customers.store') }}"
                    class="max-w-3xl"
                >

                    @csrf
                    @isset($customer)
                        @method('PUT')
                    @endisset


                    {{-- Customer Information --}}
                    <div class="rounded-xl border border-gray-200 bg-white">

                        <div class="border-b border-gray-100 px-5 py-4">

                            <p class="font-medium">
                                Customer information
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Enter the customer's basic information.
                            </p>

                        </div>


                        <div class="p-5">

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                                {{-- First Name --}}
                                <div>

                                    <label
                                        for="first_name"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        First name
                                    </label>

                                    <input
                                        id="first_name"
                                        type="text"
                                        name="first_name"
                                        value="{{ old('first_name', $customer->first_name ?? '') }}"
                                        placeholder="Juan"
                                        class="mt-1.5 block w-full rounded-lg border border-gray-200
                                               px-3 py-2.5 text-sm
                                               placeholder:text-gray-400
                                               focus:border-gray-400 focus:outline-none
                                               focus:ring-2 focus:ring-gray-100"
                                    >

                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />

                                </div>

                                <div>

                                    <label
                                        for="middle_name"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Middle name
                                    </label>

                                    <input
                                        id="middle_name"
                                        type="text"
                                        name="middle_name"
                                        value="{{ old('middle_name', $customer->middle_name ?? '') }}"
                                        placeholder="Juan"
                                        class="mt-1.5 block w-full rounded-lg border border-gray-200
                                               px-3 py-2.5 text-sm
                                               placeholder:text-gray-400
                                               focus:border-gray-400 focus:outline-none
                                               focus:ring-2 focus:ring-gray-100"
                                    >

                                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />

                                </div>


                                {{-- Last Name --}}
                                <div>

                                    <label
                                        for="last_name"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Last name
                                    </label>

                                    <input
                                        id="last_name"
                                        type="text"
                                        name="last_name"
                                        value="{{ old('last_name', $customer->last_name ?? '') }}"
                                        placeholder="Dela Cruz"
                                        class="mt-1.5 block w-full rounded-lg border border-gray-200
                                               px-3 py-2.5 text-sm
                                               placeholder:text-gray-400
                                               focus:border-gray-400 focus:outline-none
                                               focus:ring-2 focus:ring-gray-100"
                                    >

                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />

                                </div>


                                {{-- Contact Number --}}
                                <div>

                                    <label
                                        for="contact_number"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Contact number
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i class="ti ti-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                                        <input
                                            id="contact_number"
                                            type="text"
                                            name="contact_number"
                                            value="{{ old('contact_number', $customer->contact_number ?? '') }}"
                                            placeholder="09XXXXXXXXX"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400 focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />

                                </div>


                                {{-- Address --}}
                                <div class="sm:col-span-2">

                                    <label
                                        for="address"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Address
                                    </label>

                                    <textarea
                                        id="address"
                                        name="address"
                                        rows="3"
                                        placeholder="Enter customer's address"
                                        class="mt-1.5 block w-full resize-none rounded-lg border border-gray-200
                                               px-3 py-2.5 text-sm
                                               placeholder:text-gray-400
                                               focus:border-gray-400 focus:outline-none
                                               focus:ring-2 focus:ring-gray-100"
                                    >{{ old('address', $customer->address ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('admin.customers') }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   border border-gray-200 px-4 py-2.5 text-sm
                                   text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg bg-gray-900 px-4 py-2.5 text-sm
                                   font-medium text-white hover:bg-gray-800"
                        >
                            <i class="ti ti-user-plus"></i>
                            {{ isset($customer) ? 'Save changes' : 'Add customer' }}
                        </button>

                    </div>

                </form>

            </main>

        </div>

    </div>

</x-app-layout>