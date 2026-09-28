<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\JobOrderApproval;
use App\Models\JobOrderAssignment;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Schema;

foreach (['customers', 'vehicles', 'staff', 'services', 'job_orders', 'job_order_services', 'job_order_assignments', 'job_order_approvals'] as $table) {
    echo "TABLE:$table\n";
    dump(Schema::getColumnListing($table));
    echo "---\n";
}

foreach ([
    'Customer::first()' => fn () => Customer::first(),
    'Vehicle::first()' => fn () => Vehicle::first(),
    'Staff::first()' => fn () => Staff::first(),
    'Service::first()' => fn () => Service::first(),
    'JobOrder::with([\'customer\', \'vehicle\'])->first()' => fn () => JobOrder::with(['customer', 'vehicle'])->first(),
    'JobOrderAssignment::with([\'jobOrder\', \'staff\'])->first()' => fn () => JobOrderAssignment::with(['jobOrder', 'staff'])->first(),
    'JobOrderApproval::with([\'jobOrder\', \'approvedBy\'])->first()' => fn () => JobOrderApproval::with(['jobOrder', 'approvedBy'])->first(),
] as $label => $callback) {
    echo "QUERY:$label\n";
    try {
        dump($callback());
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n";
    }
    echo "---\n";
}
