<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class GymEntry
 * 
 * @property int $id
 * @property int $user_id
 * @property Carbon $check_in_at
 * @property Carbon|null $check_out_at
 * @property int|null $duration_minutes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class GymEntry extends Model
{
	use HasFactory;
	protected $table = 'gym_entries';

	protected $casts = [
		'user_id' => 'int',
		'check_in_at' => 'datetime',
		'check_out_at' => 'datetime',
		'duration_minutes' => 'int'
	];

	protected $fillable = [
		'user_id',
		'check_in_at',
		'check_out_at',
		'duration_minutes'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
