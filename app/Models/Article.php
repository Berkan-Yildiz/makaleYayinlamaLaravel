<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getTagsToArrayAttribute(): array{
        if (!is_null($this->attributes['tags'])) {
            return explode(',', $this->attributes['tags']);
        }
        return $this->attributes['tags'];
    }
//    public function tagsToStringAttribute(): string{
//        return is_array($this->tags) ? implode(",", $this->tags) : "";
//    }
    public function getFormatPublishDateAttribute():string
    {
        return Carbon::parse($this->attributes['publish_date'])->format("d-m-Y H:i");
    }
    public function category():HasOne{
        return $this->HasOne(Category::class, 'id', 'category_id');
    }
    public function user():HasOne{
        return $this->hasOne(User::class, "id", "user_id");
    }
    public function comments():HasMany{
        return $this->hasMany(ArticleComment::class, 'article_id', 'id');
    }
    public function articleLikes():HasMany{
        return $this->hasMany(UserLikeArticle::class, 'article_id', 'id');
    }

    public function logs():MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function scopeStatus($query, $status){
        if(!is_null($status)){
            return $query->where('status', $status);
        }
    }
    public function scopeCategory($query, $category_id){
        if(!is_null($category_id)){
            return $query->where('category_id', $category_id);
        }
    }
    public function scopeUser($query, $user_id){
        if(!is_null($user_id)){
            return $query->where('user_id', $user_id);
        }
    }
}
