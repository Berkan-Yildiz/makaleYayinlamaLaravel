<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public CONST ACTIONS = [
        'create',
        'update',
        'delete',
        'forceDelete',
        'restore',
        'login',
        'logout',
        'verify user',
        'password reset mail send'
    ];

    public CONST MODELS = [
        Article::class,
        User::class,
        Category::class,
        Settings::class,
        ArticleComment::class,
    ];

    public function loggable() : MorphTo
    {
        return $this->morphTo();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    protected $dateFormat = 'Y-m-d H:i:s.';

}
