<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $query = Staff::with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('staff_first', 'like', "%{$search}%")
                    ->orWhere('staff_middle', 'like', "%{$search}%")
                    ->orWhere('staff_last', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }

        $staff = $query->orderBy('staff_id')->get();

        return view('admin.staff', compact('staff'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = null;

        if ($request->filled('email') || $request->filled('password')) {
            $user = User::create([
                'name' => trim(($data['staff_first'] ?? '').' '.($data['staff_middle'] ?? '').' '.($data['staff_last'] ?? '')),
                'first_name' => $data['staff_first'],
                'middle_name' => $data['staff_middle'] ?? null,
                'last_name' => $data['staff_last'],
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        }

        $staffData = [
            'user_id' => $user?->id,
            'staff_first' => $data['staff_first'],
            'staff_middle' => $data['staff_middle'] ?? null,
            'staff_last' => $data['staff_last'],
            'contact_number' => $data['contact_number'],
            'address' => $data['address'],
            'created_by' => auth()->id(),
        ];

        Staff::create($staffData);

        return redirect()->route('admin.staff')->with('success', 'Staff member created successfully.');
    }

    public function edit(Staff $staff): View
    {
        $staff->load('user');

        return view('admin.users.create', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $data = $request->validated();

        if ($request->filled(['email', 'password']) || $request->filled('email')) {
            $userData = [
                'name' => trim(($data['staff_first'] ?? '').' '.($data['staff_middle'] ?? '').' '.($data['staff_last'] ?? '')),
                'first_name' => $data['staff_first'],
                'middle_name' => $data['staff_middle'] ?? null,
                'last_name' => $data['staff_last'],
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($staff->user) {
                $staff->user()->update($userData);
            } else {
                $user = User::create([...$userData, 'password' => Hash::make($request->password)]);
                $staff->update(['user_id' => $user->id]);
            }
        }

        $staff->update([
            'staff_first' => $data['staff_first'],
            'staff_middle' => $data['staff_middle'] ?? null,
            'staff_last' => $data['staff_last'],
            'contact_number' => $data['contact_number'],
            'address' => $data['address'],
        ]);

        return redirect()->route('admin.staff')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        if ($staff->user) {
            $staff->user->delete();
        }

        $staff->delete();

        return redirect()->route('admin.staff')->with('success', 'Staff member deleted successfully.');
    }
}
