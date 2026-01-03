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
 * Class Event
 * 
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $venue
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string|null $image
 * @property string $event_type
 * @property string $status
 * @property int|null $added_by
 * @property Carbon|null $added_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Event extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'events';

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'added_by' => 'int',
		'added_at' => 'datetime'
	];

	protected $fillable = [
		'title',
		'description',
		'venue',
		'start_date',
		'end_date',
		'start_time',
		'end_time',
		'image',
		'event_type',
		'status',
		'added_by',
		'added_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'added_by');
	}
}
