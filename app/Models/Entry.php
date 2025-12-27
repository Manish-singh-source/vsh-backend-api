<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'staff_id',
        'entry_mode',
        'entry_type',
        'vehicle_number',
        'notes',
        'entry_date',
        'entry_time',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'entry_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
