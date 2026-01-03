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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            
            $table->string('wing_name')->nullable();
            
            $table->boolean('is_bookable')->default(false);
            
            $table->enum('status', [
                'active', 'inactive', 'unavailable', 
                'damaged', 'under_maintenance'
            ])->default('inactive');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_bookable', 'status', 'wing_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
