<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_user_id',
        'status'
    ];

    public function requestedUser()
    {
        return $this->belongsTo(User::class, 'requested_user_id');
    }
}

