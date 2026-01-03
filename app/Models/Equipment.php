<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Equipment
 * 
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string|null $description
 * @property string|null $wing_name
 * @property bool $is_bookable
 * @property string $status
 * @property int|null $added_by
 * @property Carbon|null $added_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property Collection|Booking[] $bookings
 *
 * @package App\Models
 */
class Equipment extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'equipment';

	protected $casts = [
		'is_bookable' => 'bool',
		'added_by' => 'int',
		'added_at' => 'datetime'
	];

	protected $fillable = [
		'name',
		'image',
		'description',
		'wing_name',
		'is_bookable',
		'status',
		'added_by',
		'added_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'added_by');
	}

	public function bookings()
	{
		return $this->hasMany(Booking::class);
	}
}
