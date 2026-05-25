<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'contact_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function refrigerators()
    {
        return $this->hasMany(Refrigerator::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
