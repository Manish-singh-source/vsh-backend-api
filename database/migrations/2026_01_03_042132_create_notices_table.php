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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            
            $table->enum('notice_category', ['general', 'maintenance', 'event', 'other'])
                  ->default('other');
            
            $table->string('image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            
            $table->boolean('is_important')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['notice_category', 'is_important', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
