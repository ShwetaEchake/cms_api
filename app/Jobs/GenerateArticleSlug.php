<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GenerateArticleSlug implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article) {
        $this->article = $article;
    }

    public function handle()
    {
        $baseSlug = Str::slug($this->article->title);

        $slug = $baseSlug;
        $count = 1;
        while (Article::where('slug', $slug)->where('id', '!=', $this->article->id)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $this->article->slug = $slug;
        $this->article->save();
    }
}
