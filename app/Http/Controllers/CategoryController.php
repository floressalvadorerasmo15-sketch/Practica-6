<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->categories
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $request->user()->categories()->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Categoría creada correctamente',
            'data' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        if ($category->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if ($category->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $category->update($request->validated());

        return response()->json([
            'message' => 'Categoría actualizada correctamente',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente'
        ]);
    }
}