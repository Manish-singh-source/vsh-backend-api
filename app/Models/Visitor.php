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
 * Class Visitor
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $staff_id
 * @property string $name
 * @property string $phone
 * @property string|null $vehicle_number
 * @property string|null $purpose
 * @property Carbon $visit_date
 * @property Carbon|null $check_in_at
 * @property Carbon|null $check_out_at
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Visitor extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'visitors';

	protected $casts = [
		'user_id' => 'int',
		'staff_id' => 'int',
		'visit_date' => 'datetime',
		'check_in_at' => 'datetime',
		'check_out_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'staff_id',
		'name',
		'phone',
		'vehicle_number',
		'purpose',
		'visit_date',
		'check_in_at',
		'check_out_at',
		'status',
		'image_path',
		'face_id',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
