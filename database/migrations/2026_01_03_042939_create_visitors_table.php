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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('name');
            $table->string('phone');
            $table->string('vehicle_number')->nullable();
            $table->string('purpose')->nullable();
            
            $table->date('visit_date');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            
            $table->enum('status', [
                'expected', 'checked_in', 'checked_out', 'denied'
            ])->default('expected');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['phone', 'visit_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
