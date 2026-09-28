<x-app-layout>

    <div class="min-h-screen bg-white text-gray-900">

        <div class="flex min-h-screen">

            <x-admin-sidebar />


            <main class="flex-1 min-w-0 p-8 flex flex-col gap-6">

                <!-- Header -->
                <div class="flex items-start justify-between gap-4">

                    <div>

                        <h1 class="text-2xl font-medium">
                            Services
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Manage available repair and maintenance services
                        </p>

                    </div>


                    <a href="{{ route('admin.users.addservices') }}"
                       class="inline-flex items-center gap-2 bg-gray-900 text-white rounded-lg px-4 py-2 text-sm hover:bg-gray-800">

                        <i class="ti ti-plus"></i>
                        Add service

                    </a>

                </div>


                <!-- Search + Filter -->
                <form method="GET" action="{{ route('admin.services') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">

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
                            placeholder="Search services..."
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


                             {{-- Low-High --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                Low-High
                            </button>

                             {{-- High-Low --}}
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg
                                    px-3 py-2 text-sm text-gray-700
                                    transition hover:bg-gray-50"
                            >
                                <i class="ti ti-sort-descending text-base text-gray-400"></i>

                                High-Low
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

                                By Name
                            </button>

                        </div>

                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-800"><i class="ti ti-search"></i> Search</button>
                </form>


                <!-- Services -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="font-medium">
                            Service catalog
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $services->count() }} available services for job orders
                        </p>

                    </div>


                    <div>
                        @forelse ($services as $service)
                            <div class="flex items-center gap-4 border-b border-gray-100 px-5 py-4 last:border-0 hover:bg-gray-50">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-50">
                                    <i class="ti ti-tool text-lg text-gray-500"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium">{{ $service->job_desc }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">Service #{{ $service->service_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">₱{{ number_format((float) $service->price, 2) }}</p>
                                    <p class="text-xs text-green-600">Available</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-gray-500 hover:text-gray-900" aria-label="Edit service">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" aria-label="Delete service"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="px-5 py-8 text-center text-sm text-gray-500">No services have been added.</p>
                        @endforelse
                    </div>

                </div>

            </main>

        </div>

    </div>

</x-app-layout>