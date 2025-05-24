<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // Yeh import zaroori hai


class Article extends Model
{
    protected $fillable = ['title', 'content', 'status', 'published_at', 'author_id'];

    public function categories() {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

}
