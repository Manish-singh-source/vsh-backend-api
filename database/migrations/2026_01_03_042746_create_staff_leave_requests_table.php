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
        Schema::create('staff_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            
            $table->enum('leave_type', [
                'sick', 'casual', 'paid', 'unpaid', 
                'emergency', 'annual', 'other'
            ])->default('other');
            
            $table->date('from_date');
            $table->date('to_date');
            $table->boolean('is_half_day')->default(false);
            
            $table->text('reason')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['staff_id', 'status', 'leave_type', 'from_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_leave_requests');
    }
};
