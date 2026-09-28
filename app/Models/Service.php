<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $table = 'services';

    protected $primaryKey = 'service_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'job_desc',
        'price',
    ];

    public function jobOrders(): BelongsToMany
    {
        return $this->belongsToMany(JobOrder::class, 'job_order_services', 'service_id', 'job_order_id')
            ->using(JobOrderService::class);
    }
}
