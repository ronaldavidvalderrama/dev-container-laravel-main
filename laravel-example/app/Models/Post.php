<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "posts";

    protected $fillable = [
        'title',
        'content',
        'slug',
        'status',
        'published_at',
        'cover_image',
        'tags',
        'meta',
        'user_id'
    ];

    protected $casts = [
        'pubished_at' => 'datetime',
        'tags' => 'array',
        'meta' => 'array',
        'deleted_at' => 'deletime'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        // Tabla pivot post_category
        return $this->belongsToMany(Category::class)->using(CategoryPost::class)->withTimestamps();
    }
}