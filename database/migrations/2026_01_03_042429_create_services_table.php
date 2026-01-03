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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('service_category', [
                'parcel', 'vehicle', 'supermarket', 'grocery', 
                'garage', 'doctor', 'medical', 'other'
            ])->default('other');
            
            $table->string('phone')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('address')->nullable();
            
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['service_category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
