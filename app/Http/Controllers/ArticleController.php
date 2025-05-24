<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Jobs\GenerateArticleSlug;
use App\Jobs\GenerateArticleSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function index()
    {
        // return response()->json(Article::all());
        $user = auth()->user();

        $articles = Article::with(['categories:id,name', 'author:id,name']);
        if ($user->role === 'author') {
            $articles->where('author_id', $user->id);
        }

        return response()->json($articles->get());
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'category_ids' => 'array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'required|in:draft,published,archived',
                'published_at' => 'nullable|date',
            ]);

            $data['author_id'] = Auth::id();

            // Create article without slug and summary
            $article = Article::create($data);

            // Attach categories if any
            if (!empty($data['category_ids'])) {
                $article->categories()->sync($data['category_ids']);
            }

            // Dispatch jobs for slug and summary generation
            GenerateArticleSlug::dispatch($article);
            GenerateArticleSummary::dispatch($article);

            return response()->json($article, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Find the article, or fail with 404
            $article = Article::findOrFail($id);

            // Optional: Check if current user is the author or admin (if needed)
            if (auth()->id() !== $article->author_id && auth()->user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Validate incoming data (all fields optional for update)
            $data = $request->validate([
                'title' => 'sometimes|required|string',
                'content' => 'sometimes|required|string',
                'category_ids' => 'sometimes|array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'sometimes|required|in:draft,published,archived',
                'published_at' => 'nullable|date',
            ]);

            // Update article data
            $article->update($data);

            // Sync categories if provided
            if (isset($data['category_ids'])) {
                $article->categories()->sync($data['category_ids']);
            }

            // Dispatch async jobs if title or content updated (slug and summary depend on these)
            if (isset($data['title']) || isset($data['content'])) {
                GenerateArticleSlug::dispatch($article);
                GenerateArticleSummary::dispatch($article);
            }

            return response()->json($article);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Article not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

     public function destroy(Article $article)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $article->author_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->delete();
        return response()->json([
            'message' => 'Article deleted successfully.',
        ], 200);
    }



    public function listArticles(Request $request)
    {
        $query = Article::query();
        $user = auth()->user();

        if ($user->role === 'author') {
            $query->where('author_id', $user->id);
        }

        if ($request->filled('categories')) {
            $categories = $request->input('categories');
            $query->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('categories.id', $categories);  // or use slug if needed
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('published_at', [
                $request->input('date_from'),
                $request->input('date_to')
            ]);
        } elseif ($request->filled('date_from')) {
            $query->where('published_at', '>=', $request->input('date_from'));
        } elseif ($request->filled('date_to')) {
            $query->where('published_at', '<=', $request->input('date_to'));
        }

        $articles = $query->select('id', 'title', 'content', 'published_at', 'author_id')
                        ->with([
                            'categories:id,name',
                            'author:id,name'
                        ])
                        ->get();

        return response()->json($articles);
    }

}
