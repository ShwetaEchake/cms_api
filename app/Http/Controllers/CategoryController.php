<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        try {
            $categories = Category::latest()->paginate(10);

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Fetched category list successfully',
                'data'          => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Category List Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to fetch category list',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:categories,name',
            ]);

             $category = Category::create($data);

             return response()->json([
                    'message' => 'Category added successfully',
                    'category' => $category
                ],201);

        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()],403);
        }
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(Request $request, Category $category)
    {
         try {
            $data = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $category->id,
            ]);
            $category->update($data);

             return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Category deleted successfully',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'response_code' => 404,
                'status'        => 'error',
                'message'       => 'Category not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to delete category',
            ], 500);
        }
    }
}
