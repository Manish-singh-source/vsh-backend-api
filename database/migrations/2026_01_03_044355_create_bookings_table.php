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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // User always required
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Equipment: required for 'equipment', NULL for 'gym_visit'
            $table->foreignId('equipment_id')->nullable()->constrained()->nullOnDelete();

            // Booking type determines equipment usage
            $table->enum('booking_type', ['equipment', 'gym_visit'])->default('equipment');
            $table->string('purpose')->nullable(); // 'Morning workout', 'Hall meeting'
            $table->integer('duration_minutes')->nullable();

            // Date/Time slots
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');

            // Status workflow
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Prevent double booking (equipment_id NULL-safe)
            $table->unique([
                'user_id',        // User can't double book themselves
                'booking_type',
                'start_date',
                'start_time',
                'end_time'
            ], 'user_slot_unique');

            // For equipment bookings only
            $table->unique([
                'equipment_id',
                'booking_type',
                'start_date',
                'start_time',
                'end_time'
            ], 'equipment_slot_unique');

            $table->index(['user_id', 'status']);
            $table->index(['booking_type', 'start_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
