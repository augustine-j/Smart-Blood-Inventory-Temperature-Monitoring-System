<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemperatureAlert extends Model
{
    protected $fillable = [
        'refrigerator_id',
        'alert_type',
        'message',
        'started_at',
        'ended_at',
        'duration_minutes',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function refrigerator()
    {
        return $this ->belongsTo(Refrigerator::class);
    }
}
