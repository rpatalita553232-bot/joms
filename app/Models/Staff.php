<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'staff_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'staff_first',
        'staff_middle',
        'staff_last',
        'contact_number',
        'address',
        'created_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobOrderAssignments(): HasMany
    {
        return $this->hasMany(JobOrderAssignment::class, 'staff_id', 'staff_id');
    }
}
