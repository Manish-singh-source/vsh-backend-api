<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * 
 * @property int $id
 * @property string $user_code
 * @property string|null $name
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property string|null $profile_image
 * @property string $role
 * @property string|null $wing_name
 * @property string|null $flat_no
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $otp
 * @property Carbon|null $otp_expiry
 * @property bool $is_verified
 * @property string|null $qr_code_image
 * @property string $status
 * @property bool $is_tenant_added
 * @property int|null $owner_id
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property Collection|Advertisement[] $advertisements
 * @property Collection|Booking[] $bookings
 * @property Collection|Complaint[] $complaints
 * @property Collection|Equipment[] $equipment
 * @property Collection|Event[] $events
 * @property Collection|FamilyMember[] $family_members
 * @property Collection|GateEntry[] $gate_entries
 * @property Collection|GymEntry[] $gym_entries
 * @property Collection|GymVisitBooking[] $gym_visit_bookings
 * @property Collection|Notice[] $notices
 * @property Collection|Service[] $services
 * @property Collection|StaffLeaveRequest[] $staff_leave_requests
 * @property Collection|StaffTask[] $staff_tasks
 * @property Collection|User[] $users
 * @property Collection|Visitor[] $visitors
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
	use SoftDeletes, HasFactory, HasRoles;
	protected $table = 'users';

	protected $casts = [
		'otp_expiry' => 'datetime',
		'is_verified' => 'bool',
		'is_tenant_added' => 'bool',
		'owner_id' => 'int',
		'approved_by' => 'int',
		'approved_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'user_code',
		'name',
		'full_name',
		'phone',
		'email',
		'profile_image',
		'role',
		'wing_name',
		'flat_no',
		'password',
		'remember_token',
		'otp',
		'otp_expiry',
		'is_verified',
		'qr_code_image',
		'status',
		'is_tenant_added',
		'owner_id',
		'approved_by',
		'approved_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_id');
	}

	public function advertisements()
	{
		return $this->hasMany(Advertisement::class, 'added_by');
	}

	public function bookings()
	{
		return $this->hasMany(Booking::class);
	}

	public function complaints()
	{
		return $this->hasMany(Complaint::class);
	}

	public function equipment()
	{
		return $this->hasMany(Equipment::class, 'added_by');
	}

	public function events()
	{
		return $this->hasMany(Event::class, 'added_by');
	}

	public function family_members()
	{
		return $this->hasMany(FamilyMember::class);
	}

	public function gate_entries()
	{
		return $this->hasMany(GateEntry::class);
	}

	public function gym_entries()
	{
		return $this->hasMany(GymEntry::class);
	}

	public function gym_visit_bookings()
	{
		return $this->hasMany(GymVisitBooking::class);
	}

	public function notices()
	{
		return $this->hasMany(Notice::class, 'added_by');
	}

	public function services()
	{
		return $this->hasMany(Service::class, 'added_by');
	}

	public function staff_leave_requests()
	{
		return $this->hasMany(StaffLeaveRequest::class, 'staff_id');
	}

	public function staff_tasks()
	{
		return $this->hasMany(StaffTask::class, 'assigned_to');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'owner_id');
	}

	public function visitors()
	{
		return $this->hasMany(Visitor::class);
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
