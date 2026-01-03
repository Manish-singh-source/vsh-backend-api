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
 * Class FamilyMember
 * 
 * @property int $id
 * @property int $user_id
 * @property string $user_code
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string $relation_with_user
 * @property string|null $profile_image
 * @property string|null $qr_code_image
 * @property string $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class FamilyMember extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'family_members';

	protected $casts = [
		'user_id' => 'int',
		'approved_by' => 'int',
		'approved_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'user_code',
		'full_name',
		'email',
		'phone',
		'relation_with_user',
		'profile_image',
		'qr_code_image',
		'status',
		'approved_by',
		'approved_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
