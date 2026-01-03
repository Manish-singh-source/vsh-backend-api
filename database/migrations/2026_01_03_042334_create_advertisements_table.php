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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            
            $table->string('image')->nullable();
            $table->string('redirect_url')->nullable();
            
            $table->date('start_date');
            $table->date('end_date');
            
            $table->boolean('is_important')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_important', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
