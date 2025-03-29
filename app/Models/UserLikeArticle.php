<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikeArticle extends Model
{
    /** @use HasFactory<\Database\Factories\UserLikeArticleFactory> */
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
}
