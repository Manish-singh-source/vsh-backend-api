<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class StaffLeaveRequest
 * 
 * @property int $id
 * @property int $staff_id
 * @property string $leave_type
 * @property Carbon $from_date
 * @property Carbon $to_date
 * @property bool $is_half_day
 * @property string|null $reason
 * @property string $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class StaffLeaveRequest extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'staff_leave_requests';

	protected $casts = [
		'staff_id' => 'int',
		'from_date' => 'datetime',
		'to_date' => 'datetime',
		'is_half_day' => 'bool',
		'approved_by' => 'int',
		'approved_at' => 'datetime'
	];

	protected $fillable = [
		'staff_id',
		'leave_type',
		'from_date',
		'to_date',
		'is_half_day',
		'reason',
		'status',
		'approved_by',
		'approved_at',
		'rejection_reason'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'staff_id');
	}
}
