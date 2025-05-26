<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Controllers\API\BaseController;


class CategoryController extends BaseController
{

    public function index()
    {
        $categories = Category::latest()->get();

        return $this->sendResponse($data,'Fetched category list successfully');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name',
            ]);

            $category = Category::create([
                'name' => $validated['name'],
            ]);

            return response()->json([
                'response_code' => 201,
                'status'        => 'success',
                'message'       => 'Category created successfully',
                'data'          => $category,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Category Create Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to create category',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Category fetched successfully',
                'data'          => $category,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'response_code' => 404,
                'status'        => 'error',
                'message'       => 'Category not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Category Fetch Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to fetch category',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $id,
            ]);

            $category = Category::findOrFail($id);
            $category->update([
                'name' => $validated['name'],
            ]);

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Category updated successfully',
                'data'          => $category,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'response_code' => 404,
                'status'        => 'error',
                'message'       => 'Category not found',
            ], 404);

        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to update category',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
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
