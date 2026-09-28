<x-app-layout>
    <div class="min-h-screen bg-white text-gray-900">
        <div class="flex min-h-screen">
            @if(auth()->user() && auth()->user()->role === 'mechanic')
                <x-mechanic-sidebar />
            @elseif(auth()->user() && auth()->user()->role === 'supervisor')
                <x-supervisor-sidebar />
            @else
                <x-admin-sidebar />
            @endif

            <main class="flex-1 min-w-0 p-8">
                <div class="mx-auto max-w-5xl space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Job Order</p>
                            <h1 class="text-2xl font-medium">JO-{{ $jobOrder->job_order_id }}</h1>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                            @if($jobOrder->status === 'pending') bg-blue-50 text-blue-700
                            @elseif($jobOrder->status === 'approved') bg-green-50 text-green-700
                            @elseif($jobOrder->status === 'rejected') bg-red-50 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($jobOrder->status) }}
                        </span>
                    </div>

                    @if(session('success'))
                        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-white p-5">
                            <h2 class="mb-4 text-lg font-medium">Customer and vehicle</h2>
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Customer</dt>
                                    <dd class="font-medium text-right">
                                        {{ $jobOrder->customer?->first_name ?? '' }} {{ $jobOrder->customer?->middle_name ?? '' }} {{ $jobOrder->customer?->last_name ?? '' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Vehicle</dt>
                                    <dd class="font-medium text-right">{{ $jobOrder->vehicle?->make ?? 'N/A' }} {{ $jobOrder->vehicle?->engine_model ?? '' }} ({{ $jobOrder->vehicle?->plate_number ?? 'N/A' }})</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Date issued</dt>
                                    <dd>{{ $jobOrder->date_issued ? \\Illuminate\\Support\\Facades\\Date::parse($jobOrder->date_issued)->format('M d, Y') : 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Expected completion</dt>
                                    <dd>{{ $jobOrder->expected_cmpl_date ? \\Illuminate\\Support\\Facades\\Date::parse($jobOrder->expected_cmpl_date)->format('M d, Y') : 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-5">
                            <h2 class="mb-4 text-lg font-medium">Service</h2>
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Service</dt>
                                    <dd class="font-medium text-right">{{ $jobOrder->services->first()?->job_desc ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Total cost</dt>
                                    <dd class="font-medium text-right">₱{{ number_format((float) ($jobOrder->total_cost ?? 0), 2) }}</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500">Created by</dt>
                                    <dd>{{ $jobOrder->creator?->name ?? 'Unknown' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h2 class="mb-3 text-lg font-medium">Problem description</h2>
                        <p class="text-sm leading-6 text-gray-700">{{ $jobOrder->problem_description }}</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h2 class="mb-3 text-lg font-medium">Remarks</h2>
                        <p class="text-sm leading-6 text-gray-700">{{ $jobOrder->remarks ?: 'No remarks yet.' }}</p>
                    </div>

                    @if($jobOrder->jobOrderApprovals->isNotEmpty())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                            <h2 class="mb-3 text-lg font-medium text-red-800">Supervisor feedback</h2>
                            @foreach($jobOrder->jobOrderApprovals as $approval)
                                <div class="rounded-lg bg-white p-3">
                                    <div class="mb-1 flex items-center justify-between gap-4 text-sm">
                                        <span class="font-medium text-red-700">{{ ucfirst($approval->status) }}</span>
                                        <span class="text-gray-500">{{ $approval->action_date ? \Illuminate\Support\Facades\Date::parse($approval->action_date)->format('M d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $approval->remarks ?: 'No remarks provided.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(auth()->user()?->isSupervisor())
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                            <h2 class="mb-3 text-lg font-medium">Supervisor actions</h2>

                            @if($jobOrder->status === 'pending')
                                <div class="space-y-4">
                                    <form method="POST" action="{{ route('supervisor.job-orders.approve', $jobOrder) }}" class="flex flex-wrap items-center gap-3">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700">Approve</button>
                                    </form>

                                    <form method="POST" action="{{ route('supervisor.job-orders.reject', $jobOrder) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label for="supervisor-reject-remarks" class="mb-1 block text-sm font-medium text-gray-700">Rejection remarks</label>
                                            <textarea id="supervisor-reject-remarks" name="remarks" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-100" placeholder="Briefly explain why this is being rejected"></textarea>
                                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                        </div>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">Reject</button>
                                    </form>
                                </div>
                            @elseif($jobOrder->status === 'approved')
                                <form method="POST" action="{{ route('supervisor.job-orders.assign', $jobOrder) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="supervisor_assign_staff" class="mb-1 block text-sm font-medium text-gray-700">Assign mechanic</label>
                                        <select id="supervisor_assign_staff" name="staff_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                            <option value="">Select mechanic</option>
                                            @foreach(\App\Models\Staff::query()->with('user')->whereHas('user', fn ($query) => $query->where('role', 'mechanic'))->orderBy('staff_last')->get() as $staff)
                                                <option value="{{ $staff->staff_id }}">{{ trim(($staff->staff_first ?? '').' '.($staff->staff_middle ?? '').' '.($staff->staff_last ?? '')) ?: ($staff->user?->name ?? 'Mechanic') }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('staff_id')" class="mt-2" />
                                    </div>

                                    <div>
                                        <label for="supervisor_assign_remarks" class="mb-1 block text-sm font-medium text-gray-700">Assignment remarks</label>
                                        <textarea id="supervisor_assign_remarks" name="remarks" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Optional assignment notes"></textarea>
                                        <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                    </div>

                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800">Assign mechanic</button>
                                </form>
                            @elseif($jobOrder->status === 'in_progress')
                                <div class="rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-blue-700">
                                    This job order is already assigned and in progress.
                                </div>
                            @else
                                <div class="rounded-lg border border-gray-200 bg-white p-3 text-sm text-gray-600">
                                    No supervisor actions are available for this status.
                                </div>
                            @endif
                        </div>
                    @elseif(auth()->user()?->role === 'mechanic')
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                            <h2 class="mb-3 text-lg font-medium">Update this job order</h2>
                            <form method="POST" action="{{ route('mechanic.job-orders.update', $jobOrder) }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="problem_description" class="mb-1 block text-sm font-medium text-gray-700">Problem description</label>
                                    <textarea id="problem_description" name="problem_description" rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('problem_description', $jobOrder->problem_description) }}</textarea>
                                    <x-input-error :messages="$errors->get('problem_description')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="remarks" class="mb-1 block text-sm font-medium text-gray-700">Remarks</label>
                                    <textarea id="remarks" name="remarks" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('remarks', $jobOrder->remarks) }}</textarea>
                                    <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="expected_cmpl_date" class="mb-1 block text-sm font-medium text-gray-700">Expected completion date</label>
                                    <input id="expected_cmpl_date" name="expected_cmpl_date" type="date" min="{{ $jobOrder->date_issued }}" value="{{ old('expected_cmpl_date', $jobOrder->expected_cmpl_date) }}" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <x-input-error :messages="$errors->get('expected_cmpl_date')" class="mt-2" />
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800">Save changes</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
