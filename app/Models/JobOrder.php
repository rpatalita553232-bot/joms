<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOrder extends Model
{
    protected $table = 'job_orders';

    protected $primaryKey = 'job_order_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'cust_id',
        'vehicle_id',
        'created_by',
        'problem_description',
        'remarks',
        'status',
        'date_issued',
        'expected_cmpl_date',
        'total_cost',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'job_order_services', 'job_order_id', 'service_id')
            ->using(JobOrderService::class);
    }

    public function jobOrderAssignments(): HasMany
    {
        return $this->hasMany(JobOrderAssignment::class, 'job_order_id', 'job_order_id');
    }

    public function jobOrderApprovals(): HasMany
    {
        return $this->hasMany(JobOrderApproval::class, 'job_order_id', 'job_order_id');
    }
}
