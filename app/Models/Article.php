<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Article extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'status', 'published_at', 'author_id'];

    public function categories() {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

}
