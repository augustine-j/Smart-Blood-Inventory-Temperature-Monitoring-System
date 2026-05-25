<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBag extends Model
{
    protected $fillable = [
        'refrigerator_id',
        'bag_number',
        'blood_group',
        'donor_name',
        'collection_date',
        'expiry_date',
        'quantity_ml',
        'status',

    ];

    protected $casts =[
        'collection_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast() || $this->status === 'expired';
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return now()->startOfDay()->diffInDays($this->expiry_date, false);
    }

    public function setBloodGroupAttribute($value): void
    {
        $this->attributes['blood_group'] = strtoupper($value);
    }
}
