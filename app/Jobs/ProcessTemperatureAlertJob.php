<?php

namespace App\Jobs;

use App\Events\CriticalTemperatureAlertCreated;
use App\Models\Refrigerator;
use App\Models\TemperatureAlert;
use App\Models\TemperatureLog;
use App\Models\User;
use App\Notifications\CriticalTemperatureNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class ProcessTemperatureAlertJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $refrigeratorId
    )
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $refrigerator = Refrigerator::with('bloodBank')->find($this->refrigeratorId);

         if (! $refrigerator) {
            return;
        }

        $criticalCount = TemperatureLog::where('refrigerator_id', $this->refrigeratorId)
            ->where('status', 'critical')
            ->where('recorded_at', '>=', now()->subMinutes(10))
            ->count();

        if ($criticalCount < 10) {
            return;
        }

        $alreadyOpen = TemperatureAlert::where('refrigerator_id', $this->refrigeratorId)
            ->where('status', 'open')
            ->exists();

        if ($alreadyOpen) {
            return;
        }

        $alert = TemperatureAlert::create([
             'refrigerator_id' => $this->refrigeratorId,
            'alert_type' => 'critical_temperature',
            'message' => "Refrigerator {$refrigerator->code} temperature remained above 8°C for 10 minutes.",
            'started_at' => now()->subMinutes(10),
            'duration_minutes' => 10,
            'status' => 'open', 
        ]);

        event(new CriticalTemperatureAlertCreated($alert));

        $users = User::whereIn('role', ['admin', 'monitoring'])->get();

        Notification::send($users, new CriticalTemperatureNotification($alert));
    
    }
}
