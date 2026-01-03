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
 * Class Service
 * 
 * @property int $id
 * @property string $name
 * @property string $service_category
 * @property string|null $phone
 * @property string|null $opening_hours
 * @property string|null $address
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
class Service extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'services';

	protected $casts = [
		'added_by' => 'int',
		'added_at' => 'datetime'
	];

	protected $fillable = [
		'name',
		'service_category',
		'phone',
		'opening_hours',
		'address',
		'status',
		'added_by',
		'added_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'added_by');
	}
}
