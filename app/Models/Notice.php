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
 * Class Notice
 * 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $notice_category
 * @property string|null $image
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $is_important
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
class Notice extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'notices';

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'is_important' => 'bool',
		'added_by' => 'int',
		'added_at' => 'datetime'
	];

	protected $fillable = [
		'title',
		'description',
		'notice_category',
		'image',
		'start_date',
		'end_date',
		'is_important',
		'status',
		'added_by',
		'added_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'added_by');
	}
}
