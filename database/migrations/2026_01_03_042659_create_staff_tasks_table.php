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
        Schema::create('staff_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->dateTime('due_at')->nullable();
            
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', [
                'pending', 'in_progress', 'completed', 'cancelled'
            ])->default('pending');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['priority', 'status', 'assigned_to', 'due_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_tasks');
    }
};
