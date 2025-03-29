<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = ['created_at' =>  'datetime:Y-m-d', 'updated_at' => 'datetime:Y-m-d'];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y', strtotime($value));
    }

    public function parentCategory():HasOne{
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function childCategories():HasMany
    {
        return $this->hasMany(Category::class, "parent_id", "id");
    }

    public function user():HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function articles():hasMany{
     return $this->hasMany(Article::class, 'category_id', 'id');
    }

    public function logs():MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function articlesActive():hasMany{
        return $this->hasMany(Article::class, 'category_id', 'id')
            ->where('status', 1)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ;

    }
    public function scopeName($query, $name){
        if(!is_null($name)){
            return $query->where('name', 'LIKE', "%$name%");
        }
    }
    public function scopeDescription($query, $description){
        if(!is_null($description)){
            return $query->where('description', 'LIKE', "%$description%");
        }
    }
    public function scopeSlug($query, $slug){
        if(!is_null($slug)){
            return $query->where('slug', 'LIKE', "%$slug%");
        }
    }
    public function scopeOrder($query, $order){
        if(!is_null($order)){
            return $query->where('order', 'LIKE', "%$order%");
        }
    }
    public function scopeParentCategory($query, $parentID){
        if(!is_null($parentID)){
            return $query->where('parent_id', $parentID);
        }
    }
//    public function scopeUser($query, $userID){
//        if(!is_null($userID)){
//            return $query->orWhere('user_id', $userID);
//        }
//    }
    public function scopeStatus($query, $status){
        if(!is_null($status)){
            return $query->where('status', 'LIKE', "%$status%");
        }
    }

//    public function scopeFeatureStatus($query, $featureStatus){
//        if(!is_null($featureStatus)){
//            return $query->Where('status', 'LIKE', "%$featureStatus%");
//        }
//    }

}
