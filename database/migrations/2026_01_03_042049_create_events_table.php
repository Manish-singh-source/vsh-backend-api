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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('venue');

            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('image')->nullable();
            $table->enum('event_type', ['festival', 'meeting', 'activity', 'sport', 'other'])
                ->default('other');
            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_type', 'status', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
