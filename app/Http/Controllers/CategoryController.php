<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json(Category::all());
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
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully.',
        ], 200);
    }
}
