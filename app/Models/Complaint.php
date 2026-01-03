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
 * Class Complaint
 * 
 * @property int $id
 * @property int $user_id
 * @property string $complaint_type
 * @property string $title
 * @property string|null $description
 * @property string|null $flat_no
 * @property string $priority
 * @property string|null $image
 * @property string $status
 * @property int|null $resolved_by
 * @property Carbon|null $resolved_at
 * @property string|null $resolution_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Complaint extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'complaints';

	protected $casts = [
		'user_id' => 'int',
		'resolved_by' => 'int',
		'resolved_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'complaint_type',
		'title',
		'description',
		'flat_no',
		'priority',
		'image',
		'status',
		'resolved_by',
		'resolved_at',
		'resolution_notes'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
