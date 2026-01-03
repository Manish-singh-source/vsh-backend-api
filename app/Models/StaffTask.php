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
 * Class StaffTask
 * 
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $due_at
 * @property string $priority
 * @property string $status
 * @property int|null $added_by
 * @property Carbon|null $added_at
 * @property int|null $assigned_to
 * @property Carbon|null $assigned_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class StaffTask extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'staff_tasks';

	protected $casts = [
		'due_at' => 'datetime',
		'added_by' => 'int',
		'added_at' => 'datetime',
		'assigned_to' => 'int',
		'assigned_at' => 'datetime',
		'completed_at' => 'datetime'
	];

	protected $fillable = [
		'title',
		'description',
		'due_at',
		'priority',
		'status',
		'added_by',
		'added_at',
		'assigned_to',
		'assigned_at',
		'completed_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'assigned_to');
	}
}
