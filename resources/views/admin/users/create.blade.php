<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            {{-- Admin Sidebar --}}
            <x-admin-sidebar />

            {{-- Main Content --}}
            <main class="flex-1 min-w-0 p-6 sm:p-8">

                {{-- Header --}}
                <div class="mb-6">

                    <div class="mb-3 flex items-center gap-2 text-sm text-gray-400">

                        <a
                            href="{{ route('admin.staff') }}"
                            class="transition hover:text-gray-700"
                        >
                            Staff
                        </a>

                        <i class="ti ti-chevron-right text-xs"></i>

                        <span class="text-gray-600">
                            Add staff
                        </span>

                    </div>

                    <h1 class="text-2xl font-medium">
                        Add staff
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ isset($staff) ? 'Update staff details and account.' : 'Create an account for a new staff member.' }}
                    </p>

                </div>


                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ isset($staff) ? route('admin.staff.update', $staff) : route('admin.staff.store') }}"
                    class="max-w-3xl"
                >

                    @csrf
                    @isset($staff)
                        @method('PUT')
                    @endisset


                    {{-- Account Information --}}
                    <div class="rounded-xl border border-gray-200 bg-white">

                        {{-- Section Header --}}
                        <div class="border-b border-gray-100 px-5 py-4">

                            <p class="font-medium">{{ isset($staff) ? 'Staff information' : 'Account information' }}</p>

                            <p class="mt-1 text-xs text-gray-500">
                                Enter the staff member's account details and system role.
                            </p>

                        </div>


                        {{-- Fields --}}
                        <div class="p-5">

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                {{-- First Name --}}
                                <div>

                                    <label
                                        for="staff_first"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        First name
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-user absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="staff_first"
                                            type="text"
                                            name="staff_first"
                                            value="{{ old('staff_first', $staff->staff_first ?? '') }}"
                                            required
                                            autofocus
                                            autocomplete="given-name"
                                            placeholder="Enter first name"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('staff_first')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Middle Name --}}
                                <div>

                                    <label
                                        for="staff_middle"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Middle name
                                        <span class="text-xs text-gray-400">
                                            (Optional)
                                        </span>
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-user absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="staff_middle"
                                            type="text"
                                            name="staff_middle"
                                            value="{{ old('staff_middle', $staff->staff_middle ?? '') }}"
                                            autocomplete="additional-name"
                                            placeholder="Enter middle name"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('staff_middle')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Last Name --}}
                                <div>

                                    <label
                                        for="staff_last"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Last name
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-user absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="staff_last"
                                            type="text"
                                            name="staff_last"
                                            value="{{ old('staff_last', $staff->staff_last ?? '') }}"
                                            required
                                            autocomplete="family-name"
                                            placeholder="Enter last name"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('staff_last')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Email --}}
                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Email
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-mail absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email', $staff->user?->email ?? '') }}"
                                            autocomplete="username"
                                            placeholder="staff@example.com"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('email')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Role --}}
                                <div>

                                    <label
                                        for="role"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Role
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-user-shield absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <select
                                            id="role"
                                            name="role"
                                            class="block w-full appearance-none rounded-lg
                                                   border border-gray-200 bg-white
                                                   py-2.5 pl-10 pr-9 text-sm text-gray-900
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                            <option
                                                value=""
                                                disabled
                                                {{ old('role', $staff->user?->role ?? '') ? '' : 'selected' }}
                                            >
                                                Select a role
                                            </option>

                                            {{-- Admin intentionally excluded --}}
                                            <option
                                                value="supervisor"
                                                {{ old('role', $staff->user?->role ?? '') === 'supervisor' ? 'selected' : '' }}
                                            >
                                                Supervisor
                                            </option>

                                            <option
                                                value="mechanic"
                                                {{ old('role', $staff->user?->role ?? '') === 'mechanic' ? 'selected' : '' }}
                                            >
                                                Mechanic
                                            </option>

                                        </select>

                                        <i
                                            class="ti ti-chevron-down pointer-events-none
                                                   absolute right-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('role')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Password --}}
                                <div>

                                    <label
                                        for="password"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Password
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-lock absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password"
                                            placeholder="Create password"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('password')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Confirm Password --}}
                                <div>

                                    <label
                                        for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Confirm password
                                    </label>

                                    <div class="relative mt-1.5">

                                        <i
                                            class="ti ti-lock-check absolute left-3 top-1/2
                                                   -translate-y-1/2 text-gray-400"
                                        ></i>

                                        <input
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            autocomplete="new-password"
                                            placeholder="Confirm password"
                                            class="block w-full rounded-lg border border-gray-200
                                                   py-2.5 pl-10 pr-3 text-sm text-gray-900
                                                   placeholder:text-gray-400
                                                   focus:border-gray-400
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-gray-100"
                                        >

                                    </div>

                                    <x-input-error
                                        :messages="$errors->get('password_confirmation')"
                                        class="mt-2"
                                    />

                                </div>

                                <div>
                                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact number</label>
                                    <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number', $staff->contact_number ?? '') }}" required maxlength="20" class="mt-1.5 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input id="address" name="address" type="text" value="{{ old('address', $staff->address ?? '') }}" required maxlength="255" class="mt-1.5 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Role Information --}}
                    <div class="mt-4 rounded-xl bg-gray-50 p-4">

                        <div class="flex items-start gap-3">

                            <i
                                class="ti ti-info-circle mt-0.5 text-gray-500"
                            ></i>

                            <div>

                                <p class="text-sm font-medium text-gray-700">
                                    Staff roles
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Admin manages system records. Supervisor handles
                                    job order approval and mechanic assignment. Mechanics
                                    manage their assigned job orders.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div
                        class="mt-5 flex flex-col-reverse gap-2
                               sm:flex-row sm:justify-end"
                    >

                        <a
                            href="{{ route('admin.staff') }}"
                            class="inline-flex items-center justify-center
                                   rounded-lg border border-gray-200
                                   px-4 py-2.5 text-sm text-gray-700
                                   transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg bg-gray-900 px-4 py-2.5
                                   text-sm font-medium text-white
                                   transition hover:bg-gray-800
                                   focus:outline-none focus:ring-2
                                   focus:ring-gray-300"
                        >

                            <i class="ti ti-user-plus"></i>

                            {{ isset($staff) ? 'Save changes' : 'Add staff' }}

                        </button>

                    </div>

                </form>

            </main>

        </div>

    </div>






{{-- Error Modal --}}
@if ($errors->any())
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
    >
        <div
            @click.outside="show = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100">
                    <i class="ti ti-alert-circle text-xl text-red-600"></i>
                </div>

                <div class="flex-1">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Unable to create account
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Please check the information you entered.
                    </p>

                </div>

            </div>


            {{-- Error List --}}
            <div class="mt-4 rounded-lg bg-red-50 p-3">

                <ul class="space-y-1 text-sm text-red-600">

                    @foreach ($errors->all() as $error)
                        <li class="flex gap-2">
                            <span>•</span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach

                </ul>

            </div>


            <div class="mt-6 flex justify-end">

                <button
                    type="button"
                    @click="show = false"
                    class="rounded-lg bg-gray-900 px-4 py-2.5
                           text-sm font-medium text-white
                           transition hover:bg-gray-800"
                >
                    OK
                </button>

            </div>

        </div>
    </div>
@endif


</x-app-layout>