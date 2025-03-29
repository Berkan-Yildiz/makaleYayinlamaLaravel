<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    protected $fillable = ['user_id', 'token'];

    protected $table = 'users_verify';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
