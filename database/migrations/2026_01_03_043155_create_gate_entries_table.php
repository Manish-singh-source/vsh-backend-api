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
        Schema::create('gate_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('vehicle_number')->nullable();
            $table->enum('entry_type', ['entry', 'exit'])->default('entry');
            
            $table->timestamp('entry_at');
            $table->string('purpose')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'entry_type', 'entry_at']);
            $table->index('vehicle_number');
            // $table->index('DATE(entry_at)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gate_entries');
    }
};
