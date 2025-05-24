<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateArticleSummary implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article) {
        $this->article = $article;
    }

    public function handle()
    {
        $content = $this->article->content;

        $summary = substr($content, 0, 150) . (strlen($content) > 150 ? '...' : '');

        $this->article->summary = $summary;
        $this->article->save();
    }
}
