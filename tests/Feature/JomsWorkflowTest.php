<?php

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vehicle;

function createJomsUser(string $role): User
{
    return User::factory()->create([
        'first_name' => 'Joms',
        'middle_name' => null,
        'last_name' => ucfirst($role),
        'role' => $role,
    ]);
}

/** @return array{customer: Customer, vehicle: Vehicle, service: Service, jobOrder: JobOrder} */
function createJomsJobOrder(User $creator, string $status = 'pending'): array
{
    $customer = Customer::create([
        'first_name' => 'Joan',
        'middle_name' => null,
        'last_name' => 'Santos',
        'contact_number' => '09171234567',
        'address' => 'Workshop Road',
        'created_by' => $creator->id,
    ]);

    $vehicle = Vehicle::create([
        'cust_id' => $customer->cust_id,
        'plate_number' => 'JMS-'.fake()->unique()->numerify('####'),
        'make' => 'Toyota',
        'engine_model' => 'Vios',
    ]);

    $service = Service::create([
        'job_desc' => 'Brake service',
        'price' => 2500,
    ]);

    $jobOrder = JobOrder::create([
        'cust_id' => $customer->cust_id,
        'vehicle_id' => $vehicle->vehicle_id,
        'created_by' => $creator->id,
        'problem_description' => 'Brake pedal feels soft.',
        'status' => $status,
        'date_issued' => now()->toDateString(),
        'expected_cmpl_date' => now()->addDays(3)->toDateString(),
        'total_cost' => $service->price,
    ]);

    $jobOrder->services()->attach($service->service_id);

    return compact('customer', 'vehicle', 'service', 'jobOrder');
}

it('creates a pending job order for a mechanic', function () {
    $mechanic = createJomsUser('mechanic');
    $records = createJomsJobOrder($mechanic);
    $staff = Staff::create([
        'user_id' => $mechanic->id,
        'staff_first' => 'Joan',
        'staff_last' => 'Mechanic',
        'contact_number' => '09170000001',
        'address' => 'Workshop Road',
        'created_by' => $mechanic->id,
    ]);

    $response = $this->actingAs($mechanic)->post(route('mechanic.job-orders.store'), [
        'cust_id' => $records['customer']->cust_id,
        'vehicle_id' => $records['vehicle']->vehicle_id,
        'service_id' => $records['service']->service_id,
        'problem_description' => 'Steering wheel vibrates while braking.',
        'expected_cmpl_date' => now()->addDays(4)->toDateString(),
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_orders', [
        'created_by' => $mechanic->id,
        'cust_id' => $records['customer']->cust_id,
        'status' => 'pending',
        'problem_description' => 'Steering wheel vibrates while braking.',
    ]);
    $jobOrder = JobOrder::query()
        ->where('created_by', $mechanic->id)
        ->where('problem_description', 'Steering wheel vibrates while braking.')
        ->firstOrFail();
    $this->assertDatabaseHas('job_order_assignments', [
        'job_order_id' => $jobOrder->job_order_id,
        'staff_id' => $staff->staff_id,
    ]);
});

it('rejects a job order completion date before its issue date', function () {
    $mechanic = createJomsUser('mechanic');
    $records = createJomsJobOrder($mechanic);

    $response = $this->actingAs($mechanic)->post(route('mechanic.job-orders.store'), [
        'cust_id' => $records['customer']->cust_id,
        'vehicle_id' => $records['vehicle']->vehicle_id,
        'service_id' => $records['service']->service_id,
        'problem_description' => 'Check date validation.',
        'expected_cmpl_date' => now()->subDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors('expected_cmpl_date');
    $this->assertDatabaseMissing('job_orders', [
        'created_by' => $mechanic->id,
        'problem_description' => 'Check date validation.',
    ]);
});

it('approves a pending job order and records the approval', function () {
    $mechanic = createJomsUser('mechanic');
    $supervisor = createJomsUser('supervisor');
    $jobOrder = createJomsJobOrder($mechanic)['jobOrder'];

    $response = $this->actingAs($supervisor)->post(route('supervisor.job-orders.approve', $jobOrder));

    $response->assertRedirect(route('supervisor.pending-approvals'));
    $this->assertDatabaseHas('job_orders', [
        'job_order_id' => $jobOrder->job_order_id,
        'status' => 'approved',
    ]);
    $this->assertDatabaseHas('job_order_approvals', [
        'job_order_id' => $jobOrder->job_order_id,
        'approved_by' => $supervisor->id,
        'status' => 'approved',
    ]);
});

it('rejects a pending job order and displays the remarks on its detail page', function () {
    $mechanic = createJomsUser('mechanic');
    $supervisor = createJomsUser('supervisor');
    $jobOrder = createJomsJobOrder($mechanic)['jobOrder'];
    $remarks = 'Please include the brake inspection findings.';

    $response = $this->actingAs($supervisor)->post(route('supervisor.job-orders.reject', $jobOrder), [
        'remarks' => $remarks,
    ]);

    $response->assertRedirect(route('supervisor.pending-approvals'));
    $this->assertDatabaseHas('job_orders', [
        'job_order_id' => $jobOrder->job_order_id,
        'status' => 'rejected',
        'remarks' => $remarks,
    ]);
    $this->assertDatabaseHas('job_order_approvals', [
        'job_order_id' => $jobOrder->job_order_id,
        'status' => 'rejected',
        'remarks' => $remarks,
    ]);

    $this->actingAs($mechanic)
        ->get(route('job-orders.show', $jobOrder))
        ->assertSeeText($remarks);
});

it('assigns a mechanic to an approved job order and starts it', function () {
    $admin = createJomsUser('admin');
    $mechanic = createJomsUser('mechanic');
    $supervisor = createJomsUser('supervisor');
    $jobOrder = createJomsJobOrder($admin, 'approved')['jobOrder'];
    $staff = Staff::create([
        'user_id' => $mechanic->id,
        'staff_first' => 'Taylor',
        'staff_last' => 'Mechanic',
        'contact_number' => '09170000002',
        'address' => 'Workshop Road',
        'created_by' => $admin->id,
    ]);

    $response = $this->actingAs($supervisor)->post(route('supervisor.job-orders.assign', $jobOrder), [
        'staff_id' => $staff->staff_id,
        'remarks' => 'Start with the front brakes.',
    ]);

    $response->assertRedirect(route('supervisor.AJO', $jobOrder));
    $this->assertDatabaseHas('job_orders', [
        'job_order_id' => $jobOrder->job_order_id,
        'status' => 'in_progress',
    ]);
    $this->assertDatabaseHas('job_order_assignments', [
        'job_order_id' => $jobOrder->job_order_id,
        'staff_id' => $staff->staff_id,
        'assigned_by' => $supervisor->id,
    ]);
});

it('resubmits a rejected job order as pending for its mechanic creator', function () {
    $mechanic = createJomsUser('mechanic');
    $jobOrder = createJomsJobOrder($mechanic, 'rejected')['jobOrder'];

    $response = $this->actingAs($mechanic)->put(route('mechanic.job-orders.update', $jobOrder), [
        'problem_description' => 'Brake pads inspected and measurements added.',
        'remarks' => 'Updated inspection findings.',
        'expected_cmpl_date' => now()->addDays(2)->toDateString(),
    ]);

    $response->assertRedirect(route('job-orders.show', $jobOrder));
    $this->assertDatabaseHas('job_orders', [
        'job_order_id' => $jobOrder->job_order_id,
        'status' => 'pending',
        'problem_description' => 'Brake pads inspected and measurements added.',
    ]);
});

it('forbids users from other roles from entering role-protected route groups', function (string $role, string $path) {
    $user = createJomsUser($role);

    $this->actingAs($user)
        ->get($path)
        ->assertForbidden()
        ->assertSeeText("You can't access this page");
})->with([
    'mechanic cannot access admin' => ['mechanic', '/admin/dashboard'],
    'mechanic cannot access supervisor' => ['mechanic', '/supervisor/dashboard'],
    'supervisor cannot access admin' => ['supervisor', '/admin/dashboard'],
    'supervisor cannot access mechanic' => ['supervisor', '/mechanic/dashboard'],
    'admin cannot access supervisor' => ['admin', '/supervisor/dashboard'],
    'admin cannot access mechanic' => ['admin', '/mechanic/dashboard'],
]);

it('creates, updates, and deletes a customer as an admin', function () {
    $admin = createJomsUser('admin');

    $this->actingAs($admin)->post(route('admin.customers.store'), [
        'first_name' => 'Casey',
        'middle_name' => 'M',
        'last_name' => 'Customer',
        'contact_number' => '09171112222',
        'address' => 'Unit 10',
    ])->assertRedirect(route('admin.customers'));

    $customer = Customer::query()->where('first_name', 'Casey')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.customers.update', $customer), [
        'first_name' => 'Updated',
        'middle_name' => null,
        'last_name' => 'Customer',
        'contact_number' => '09173334444',
        'address' => 'Unit 11',
    ])->assertRedirect(route('admin.customers'));

    expect($customer->fresh()->first_name)->toBe('Updated');

    $this->actingAs($admin)->delete(route('admin.customers.destroy', $customer))
        ->assertRedirect(route('admin.customers'));
    $this->assertModelMissing($customer);
});

it('renders the custom page when a route does not exist', function () {
    $this->get('/this-route-does-not-exist')
        ->assertNotFound()
        ->assertSeeText("We couldn't find that page");
});

it('creates, updates, and deletes a vehicle as an admin', function () {
    $admin = createJomsUser('admin');
    $customer = Customer::create([
        'first_name' => 'Vehicle',
        'last_name' => 'Owner',
        'contact_number' => '09170000003',
        'address' => 'Shop',
        'created_by' => $admin->id,
    ]);
    $vehicleData = [
        'cust_id' => $customer->cust_id,
        'plate_number' => 'NEW-1234',
        'make' => 'Honda',
        'engine_model' => 'Civic',
    ];

    $this->actingAs($admin)->post(route('admin.vehicles.store'), $vehicleData)
        ->assertRedirect(route('admin.vehicles'));

    $vehicle = Vehicle::query()->where('plate_number', 'NEW-1234')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.vehicles.update', $vehicle), [
        ...$vehicleData,
        'plate_number' => 'NEW-5678',
    ])->assertRedirect(route('admin.vehicles'));

    expect($vehicle->fresh()->plate_number)->toBe('NEW-5678');

    $this->actingAs($admin)->delete(route('admin.vehicles.destroy', $vehicle))
        ->assertRedirect(route('admin.vehicles'));
    $this->assertModelMissing($vehicle);
});

it('creates, updates, and deletes a staff member as an admin', function () {
    $admin = createJomsUser('admin');
    $staffData = [
        'staff_first' => 'Morgan',
        'staff_middle' => null,
        'staff_last' => 'Staff',
        'contact_number' => '09175556666',
        'address' => 'Shop floor',
    ];

    $this->actingAs($admin)->post(route('admin.staff.store'), $staffData)
        ->assertRedirect(route('admin.staff'));

    $staff = Staff::query()->where('staff_first', 'Morgan')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.staff.update', $staff), [
        ...$staffData,
        'staff_first' => 'Jordan',
    ])->assertRedirect(route('admin.staff'));

    expect($staff->fresh()->staff_first)->toBe('Jordan');

    $this->actingAs($admin)->delete(route('admin.staff.destroy', $staff))
        ->assertRedirect(route('admin.staff'));
    $this->assertModelMissing($staff);
});

it('creates, updates, and deletes a service as an admin', function () {
    $admin = createJomsUser('admin');
    $serviceData = ['job_desc' => 'Tire rotation', 'price' => '950.00'];

    $this->actingAs($admin)->post(route('admin.services.store'), $serviceData)
        ->assertRedirect(route('admin.services'));

    $service = Service::query()->where('job_desc', 'Tire rotation')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'job_desc' => 'Wheel alignment',
        'price' => '1200.00',
    ])->assertRedirect(route('admin.services'));

    expect($service->fresh()->job_desc)->toBe('Wheel alignment');
    expect((float) $service->fresh()->price)->toBe(1200.0);

    $this->actingAs($admin)->delete(route('admin.services.destroy', $service))
        ->assertRedirect(route('admin.services'));
    $this->assertModelMissing($service);
});

it('rejects non-positive service prices', function () {
    $admin = createJomsUser('admin');

    $response = $this->actingAs($admin)->post(route('admin.services.store'), [
        'job_desc' => 'Invalid service',
        'price' => '0.00',
    ]);

    $response->assertSessionHasErrors('price');
    $this->assertDatabaseMissing('services', ['job_desc' => 'Invalid service']);
});

it('rejects a duplicate vehicle plate number', function () {
    $admin = createJomsUser('admin');
    $customer = createJomsJobOrder($admin)['customer'];
    Vehicle::create([
        'cust_id' => $customer->cust_id,
        'plate_number' => 'DUP-1234',
        'make' => 'Toyota',
        'engine_model' => 'Corolla',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.vehicles.store'), [
        'cust_id' => $customer->cust_id,
        'plate_number' => 'DUP-1234',
        'make' => 'Honda',
        'engine_model' => 'Civic',
    ]);

    $response->assertSessionHasErrors('plate_number');
    expect(Vehicle::query()->where('plate_number', 'DUP-1234')->count())->toBe(1);
});
