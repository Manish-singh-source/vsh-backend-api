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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // spatie roles/permissions needs this
            $table->string('name')->nullable(); // can mirror full_name

            $table->string('role'); // app-level role string (owner, staff, etc.)
            $table->string('user_id')->unique();
            $table->string('full_name');
            $table->string('phone_number')->unique();
            $table->string('email')->unique();
            $table->string('wing_name');
            $table->string('flat_no');
            $table->string('profile_image')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('qr_code_image')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])->default('inactive');
            $table->string('password');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // unique wing + flat
            $table->unique(['wing_name', 'flat_no']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
