<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class GateEntry
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $staff_id
 * @property string|null $vehicle_number
 * @property string $entry_type
 * @property Carbon $entry_at
 * @property string|null $purpose
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class GateEntry extends Model
{
	use HasFactory;
	protected $table = 'gate_entries';

	protected $casts = [
		'user_id' => 'int',
		'staff_id' => 'int',
		'entry_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'staff_id',
		'vehicle_number',
		'entry_type',
		'entry_at',
		'purpose'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
