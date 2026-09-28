<?php

use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a mechanic to create and view a job order', function () {
    $mechanicUser = User::factory()->create([
        'role' => 'mechanic',
        'name' => 'Mechanic User',
    ]);

    $staff = Staff::create([
        'user_id' => $mechanicUser->id,
        'staff_first' => 'Mechanic',
        'staff_last' => 'User',
        'contact_number' => '09123456789',
        'address' => 'Main Shop',
        'created_by' => $mechanicUser->id,
    ]);

    $customer = Customer::create([
        'first_name' => 'Juan',
        'middle_name' => 'Dela',
        'last_name' => 'Cruz',
        'contact_number' => '09112223333',
        'address' => 'Bahay',
        'created_by' => $mechanicUser->id,
    ]);

    $vehicle = Vehicle::create([
        'cust_id' => $customer->cust_id,
        'plate_number' => 'ABC-1234',
        'make' => 'Toyota',
        'engine_model' => 'Vios',
    ]);

    $service = Service::create([
        'job_desc' => 'Brake repair',
        'price' => 2500.00,
    ]);

    $response = $this->actingAs($mechanicUser)
        ->post(route('mechanic.job-orders.store'), [
            'cust_id' => $customer->cust_id,
            'vehicle_id' => $vehicle->vehicle_id,
            'service_id' => $service->service_id,
            'problem_description' => 'Brake pedal is soft.',
            'remarks' => 'Need inspection.',
        ]);

    $response->assertRedirect();

    $jobOrder = \App\Models\JobOrder::query()->first();

    expect($jobOrder)->not->toBeNull()
        ->and($jobOrder->status)->toBe('pending')
        ->and($jobOrder->jobOrderAssignments()->where('staff_id', $staff->staff_id)->exists())->toBeTrue()
        ->and($jobOrder->services()->where('services.service_id', $service->service_id)->exists())->toBeTrue();

    $this->actingAs($mechanicUser)
        ->get(route('mechanic.MJO'))
        ->assertOk();
});
