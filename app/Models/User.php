<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'about',
        'image',
        'google_id',
        'facebook_id',
        'github_id',
        'status',
        "email_verified_at",
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeStatus($query, $status){
        if(!is_null($status)){
            return $query->where('status', $status);
        }
    }
    public function scopeIsAdmin($query, $isAdmin){
        if(!is_null($isAdmin)){
            return $query->where('is_admin', $isAdmin);
        }
    }
    public function scopeSearchText($query, $searchText){
        if(!is_null($searchText)){
            return $query->where(function ($q) use ($searchText){
                $q->where('name', 'like', '%'.$searchText.'%')
                    ->orWhere('email', 'like', '%'.$searchText.'%')
                    ->orWhere('username', 'like', '%'.$searchText.'%');
            });
        }
    }
    public function articles(): HasMany{
        return $this->hasMany(Article::class,'user_id','id');
    }
    public function categories(): HasMany{
        return $this->hasMany(Category::class,'user_id','id');
    }
    public function articleLike(): HasMany{
        return $this->hasMany(UserLikeArticle::class,'user_id','id');
    }
    public function commentLike(): HasMany{
        return $this->hasMany(UserLikeComment::class,'user_id','id');
    }
    public function hasLogs():HasMany
    {
        return $this->HasMany(Log::class, 'user_id','id');
    }
    public function logs():MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

}
