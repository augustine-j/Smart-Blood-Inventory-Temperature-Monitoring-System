<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temperature_alerts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('refrigerator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('alert_type')->default('critical_temperature');
            $table->text('message');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->unsignedInteger('duration_minutes')->default(10);

            $table->enum('status',['open','resolved'])->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['refrigerator_id','status']);
            $table->index(['started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature_alerts');
    }
};
