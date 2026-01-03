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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('complaint_type', [
                'maintenance',
                'security',
                'electrical',
                'plumbing',
                'common_area',
                'amenities',
                'parking',
                'other'
            ])->default('other');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('flat_no')->nullable();

            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('image')->nullable();

            $table->enum('status', [
                'pending',
                'in_progress',
                'resolved',
                'reopened',
                'cancelled'
            ])->default('pending');

            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['complaint_type', 'status', 'priority']);
            $table->index('flat_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
