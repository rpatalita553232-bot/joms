<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobOrderController as AdminJobOrderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Mechanic\JobOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\JobOrderController as SupervisorJobOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => app(AdminDashboardController::class)->index(),
        'supervisor' => app(SupervisorDashboardController::class)->index(),
        'mechanic' => app(MechanicDashboardController::class)->index(),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users/create', function () {
            return view('admin.users.create');
        })->name('users.create');

        Route::get('/users/addcustomer', function () {
            return view('admin.users.addcustomer');
        })->name('users.addcustomer');

        Route::get('/users/addservices', function () {
            return view('admin.users.addservices');
        })->name('users.addservices');

        Route::get('/users/addvehicles', function () {
            return view('admin.users.addvehicles');
        })->name('users.addvehicles');

        Route::post('/users', [StaffController::class, 'store'])->name('users.store');

        Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

        Route::get('/staff', [StaffController::class, 'index'])->name('staff');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

        Route::get('/job-orders', [AdminJobOrderController::class, 'index'])->name('job-orders');

        Route::get('/services', [ServiceController::class, 'index'])->name('services');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

Route::middleware(['auth', 'verified', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pending-approvals', [SupervisorJobOrderController::class, 'pendingApprovals'])->name('pending-approvals');
        Route::get('/AJO', [SupervisorJobOrderController::class, 'allJobOrders'])->name('AJO');
        Route::get('/assign-mechanic', [SupervisorJobOrderController::class, 'assignMechanic'])->name('assign-mechanic');
        Route::get('/approval-history', [SupervisorJobOrderController::class, 'approvalHistory'])->name('approval-history');

        Route::post('/job-orders/{jobOrder}/approve', [SupervisorJobOrderController::class, 'approve'])->name('job-orders.approve');
        Route::post('/job-orders/{jobOrder}/reject', [SupervisorJobOrderController::class, 'reject'])->name('job-orders.reject');
        Route::post('/job-orders/{jobOrder}/assign', [SupervisorJobOrderController::class, 'assign'])->name('job-orders.assign');
    });

Route::middleware(['auth', 'verified', 'role:mechanic'])
    ->prefix('mechanic')
    ->name('mechanic.')
    ->group(function () {
        Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');

        Route::get('/MJO', [JobOrderController::class, 'index'])->name('MJO');
        Route::get('/CJO', [JobOrderController::class, 'create'])->name('CJO');
        Route::post('/job-orders', [JobOrderController::class, 'store'])->name('job-orders.store');
        Route::put('/job-orders/{jobOrder}', [JobOrderController::class, 'update'])->name('job-orders.update');
        Route::get('/needs-revision', [JobOrderController::class, 'needsRevision'])->name('needs-revision');
    });

Route::middleware('auth')->group(function () {
    Route::get('/job-orders/{jobOrder}', [JobOrderController::class, 'show'])->name('job-orders.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
