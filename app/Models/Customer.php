<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';

    protected $primaryKey = 'cust_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'address',
        'created_by',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'cust_id', 'cust_id');
    }

    public function jobOrders(): HasMany
    {
        return $this->hasMany(JobOrder::class, 'cust_id', 'cust_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
