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
 * Class GymVisitBooking
 * 
 * @property int $id
 * @property int $user_id
 * @property Carbon $visit_date
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $status
 * @property int|null $approved_by
 * @property string|null $purpose
 * @property int $duration_minutes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class GymVisitBooking extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'gym_visit_bookings';

	protected $casts = [
		'user_id' => 'int',
		'visit_date' => 'datetime',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'approved_by' => 'int',
		'duration_minutes' => 'int'
	];

	protected $fillable = [
		'user_id',
		'visit_date',
		'start_time',
		'end_time',
		'status',
		'approved_by',
		'purpose',
		'duration_minutes'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
