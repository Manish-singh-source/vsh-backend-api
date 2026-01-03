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
            $table->string('user_code')->unique();
            
            // Basic Information
            $table->string('name')->nullable(); // can mirror full_name
            $table->string('full_name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('profile_image')->nullable();
            
            // Role & Permissions
            $table->enum('role', ['owner', 'tenant', 'admin', 'staff', 'super_admin'])
                  ->default('owner');
            
            // Residency Information
            $table->string('wing_name')->nullable(); // A, B, C, D, E
            $table->string('flat_no')->nullable();
            
            // Authentication
            $table->string('password');
            $table->rememberToken();
            
            // Verification
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->boolean('is_verified')->default(false);
            
            // Identity
            $table->string('qr_code_image')->nullable();
            
            // Status Management
            $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])
                  ->default('inactive');
            $table->boolean('is_tenant_added')->default(false);
            
            // Relationships
            $table->foreignId('owner_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['wing_name', 'flat_no']);
            $table->index(['role', 'status']);
            $table->index('owner_id');
            $table->index('approved_by');
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
