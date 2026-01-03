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
        Schema::create('gym_visit_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->date('visit_date');
            $table->time('start_time');
            $table->time('end_time'); // e.g., 1-hour slot

            $table->enum('status', ['pending', 'approved', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Gym-specific fields
            $table->string('purpose')->nullable(); // 'Cardio', 'Weights'
            $table->integer('duration_minutes')->default(60);

            $table->timestamps();
            $table->softDeletes();

            // Prevent double booking same slot
            $table->unique(['user_id', 'visit_date', 'start_time'], 'gym_slot_unique');
            $table->index(['status', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_visit_bookings');
    }
};
