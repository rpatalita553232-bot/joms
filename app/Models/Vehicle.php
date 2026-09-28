<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $primaryKey = 'vehicle_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'cust_id',
        'plate_number',
        'make',
        'engine_model',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function jobOrders(): HasMany
    {
        return $this->hasMany(JobOrder::class, 'vehicle_id', 'vehicle_id');
    }
}
