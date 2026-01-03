<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Entry
 * 
 * @property int $id
 * @property int $owner_id
 * @property int $staff_id
 * @property string $entry_mode
 * @property string $entry_type
 * @property string|null $vehicle_number
 * @property string|null $notes
 * @property Carbon $entry_date
 * @property Carbon $entry_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Entry extends Model
{
	protected $table = 'entries';

	protected $casts = [
		'owner_id' => 'int',
		'staff_id' => 'int',
		'entry_date' => 'datetime',
		'entry_time' => 'datetime'
	];

	protected $fillable = [
		'owner_id',
		'staff_id',
		'entry_mode',
		'entry_type',
		'vehicle_number',
		'notes',
		'entry_date',
		'entry_time'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'staff_id');
	}
}
