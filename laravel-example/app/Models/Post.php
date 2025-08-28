<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDelete;

class Post extends Model
{
    use HasFactory, SoftDelete;
    protected $table = 'posts';

    protected $fillable = [
        'title', 'content', 'status',
        'published_at', 'conver_image', 'tags', 'meta'
    ];

    protected $casts = [
        'published_at' => 'datatime',
        'tags' => 'array',
        'meta' => 'meta'
    ];

    public function categories() {
        // TABLA PIVOTE post_category

        return $this->belongsToMany(category::class)->using(CategoryPost::class)->withTimestamps();
    }
}
