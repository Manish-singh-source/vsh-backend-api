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
        Schema::create('gym_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamp('check_in_at');
            $table->timestamp('check_out_at')->nullable();
            $table->integer('duration_minutes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'check_in_at']);
            // $table->index('DATE(check_in_at)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_entries');
    }
};
