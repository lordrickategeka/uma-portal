<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title', 
        'post_type',
        'slug', 
        'content', 
        'branch_id',
        'image', 
        'author_id', 
        'status', 
        'published_at', 
        'views', 
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // public function images()
    // {
    //     return $this->hasMany(Image::class);  // Adjust as per your relationship
    // }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);  // Assuming you have a Comment model
    // }

    // Optionally, you can add a `comments_count` attribute to optimize for performance:
    // protected $appends = ['comments_count'];

    // public function getCommentsCountAttribute()
    // {
    //     return $this->comments()->count();
    // }


}
