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
 * Class Booking
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $equipment_id
 * @property string $booking_type
 * @property string|null $purpose
 * @property int|null $duration_minutes
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Equipment|null $equipment
 *
 * @package App\Models
 */
class Booking extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'bookings';

	protected $casts = [
		'user_id' => 'int',
		'equipment_id' => 'int',
		'duration_minutes' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'approved_by' => 'int',
		'approved_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'equipment_id',
		'booking_type',
		'purpose',
		'duration_minutes',
		'start_date',
		'end_date',
		'start_time',
		'end_time',
		'status',
		'approved_by',
		'approved_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function equipment()
	{
		return $this->belongsTo(Equipment::class);
	}
}
