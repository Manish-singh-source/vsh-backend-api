# Database Schemas 


1. users ('owner', 'tenant', 'admin', 'staff', 'super admin')
    $table->id();
    $table->string('user_code')->unique();

    $table->enum('role', ['owner', 'tenant', 'admin', 'staff', 'super_admin'])->default('owner');

    $table->string('full_name');
    $table->string('phone')->unique();
    $table->string('email')->unique();

    $table->string('wing_name')->nullable();
    $table->string('flat_no')->nullable();
    $table->string('profile_image')->nullable();

    $table->string('otp')->nullable();
    $table->timestamp('otp_expiry')->nullable();
    $table->boolean('is_verified')->default(false);

    $table->string('qr_code_image')->nullable();

    $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])->default('inactive');

    $table->boolean('is_tenant_added')->default(false);

    $table->foreignId('owner_id')->nullable()->constrained('users')->cascadeOnDelete();

    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_at')->nullable();

    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();


2. family members / rentals family members (belongs to user)

    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->string('full_name');
    $table->string('email');
    $table->string('phone');
    $table->string('relation_with_user');

    $table->string('profile_image')->nullable();
    $table->string('qr_code_image')->nullable();

    $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])->default('inactive');

    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

    $table->unique(['user_id', 'email']);
    $table->unique(['user_id', 'phone']);




5. events:
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('venue');

    <!-- 
    $table->dateTime('starts_at');
    $table->dateTime('ends_at'); 
    -->

    $table->date('start_date');
    $table->date('end_date');
    $table->time('start_time');
    $table->time('end_time');

    $table->string('image')->nullable();
    $table->enum('event_type', ['festival', 'meeting', 'activity', 'sport', 'other'])->default('other');
    $table->enum('status', ['active', 'inactive'])->default('inactive');

    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();

    $table->timestamps();
    $table->softDeletes();



6. notices: 
    $table->id();
    $table->string('title');
    $table->text('description');

    $table->enum('notice_category', ['general', 'maintenance', 'event', 'other'])->default('other');

    $table->string('image')->nullable();

    <!-- 
    $table->dateTime('starts_at');
    $table->dateTime('ends_at'); 
    -->

    $table->date('start_date');
    $table->date('end_date');

    $table->boolean('is_important')->default(false);
    $table->enum('status', ['active', 'inactive'])->default('inactive');

    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();

    $table->timestamps();
    $table->softDeletes();


7. advertisements: 
    $table->id();
    $table->string('title');
    $table->text('description');

    $table->string('image')->nullable();
    $table->string('redirect_url')->nullable();

    <!-- 
    $table->dateTime('starts_at');
    $table->dateTime('ends_at'); 
    -->

    $table->date('start_date');
    $table->date('end_date');

    $table->boolean('is_important')->default(false);
    $table->enum('status', ['active', 'inactive'])->default('inactive');

    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();

    $table->timestamps();
    $table->softDeletes();


8. services: 
    $table->id();
    $table->string('name');
    $table->enum('service_category', ['parcel', 'vehicle', 'supermarket', 'grocery', 'garage', 'doctor', 'medical', 'other'])->default('other');

    $table->string('phone')->nullable();
    <!-- $table->json('phones')->nullable(); -->
    $table->string('opening_hours')->nullable();
    $table->string('address')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('inactive');

    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

9. equipments: 
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




10. admin notifications (custom from admin)
    $table->id();
    $table->string('title');
    $table->text('message');

    $table->enum('send_to', ['all', 'owner', 'tenant', 'admin', 'staff', 'owners_and_staffs'])->default('all');

    $table->enum('status', ['active', 'inactive'])->default('inactive');
    
    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();
    
    $table->timestamps();
    $table->softDeletes();


11. staff tasks 
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();

    $table->dateTime('due_at')->nullable();

    $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
    $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');

    $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('added_at')->nullable();

    $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('assigned_at')->nullable();

    $table->timestamp('completed_at')->nullable();

    $table->timestamps();
    $table->softDeletes();



12. staff leave requests 
    $table->id();

    $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();

    $table->enum('leave_type', [
        'sick', 'casual', 'paid', 'unpaid', 'emergency', 'annual', 'other'
    ])->default('other');

    $table->date('from_date');
    $table->date('to_date');

    $table->boolean('is_half_day')->default(false);

    $table->text('reason')->nullable();

    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_at')->nullable();

    $table->text('rejection_reason')->nullable();

    $table->timestamps();
    $table->softDeletes();





13. user(owners/rentals) complaints 
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

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

    // Optional: only if complaint can be for another flat
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

    // Resolution
    $table->foreignId('resolved_by')->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('resolved_at')->nullable();
    $table->text('resolution_notes')->nullable();

    $table->timestamps();
    $table->softDeletes();




14. visitors (manual entry, pre registered owners/rentals)
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete(); // owner or tenant

    $table->foreignId('staff_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete(); // security/staff

    $table->string('name');
    $table->string('phone');

    $table->string('vehicle_number')->nullable();
    $table->string('purpose')->nullable();

    $table->date('visit_date');

    $table->timestamp('check_in_at')->nullable();
    $table->timestamp('check_out_at')->nullable();

    $table->enum('status', [
        'expected',
        'checked_in',
        'checked_out',
        'denied'
    ])->default('expected');

    $table->timestamps();
    $table->softDeletes();

    $table->index('phone');
    $table->index('visit_date');




15. bookings (manual entry, pre registered owners/rentals)
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
    $table->date('start_date');
    $table->date('end_date');
    $table->time('start_time');
    $table->time('end_time');
    $table->timestamps();
    $table->softDeletes();


    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('equipment_id')
        ->constrained('equipments')
        ->cascadeOnDelete();

    <!-- 
    $table->dateTime('starts_at');
    $table->dateTime('ends_at'); 
    -->

    $table->date('start_date');
    $table->date('end_date');
    $table->time('start_time');
    $table->time('end_time');

    $table->enum('status', [
        'pending',
        'approved',
        'rejected',
        'cancelled'
    ])->default('pending');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('approved_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

    // Prevent double booking
    $table->index(['equipment_id', 'starts_at', 'ends_at']);



16. gym_entries (gym in/out, vehicle in/out) 
17. gate_entries (vehicle in/out, manual entry, pre registered owners/rentals)
