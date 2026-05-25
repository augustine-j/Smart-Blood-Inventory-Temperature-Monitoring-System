<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemperatureLog extends Model
{
    protected $fillable = [
        'refrigerator_id',
        'temperature',
        'status',
        'recorded_at',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class);
    }

    public static function statusForTemperature(float $temperature): string
    {
        if ($temperature > 8){
            return 'critical';
        }

        if($temperature > 6){
            return 'warning';
        }

        return 'safe';
    }
}
