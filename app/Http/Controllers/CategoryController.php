<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Listar categorías del usuario autenticado
    public function index(Request $request)
    {
        $categories = $request->user()->categories;

        return response()->json($categories);
    }

    // Crear categoría
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

    // Mostrar una categoría
    public function show(Category $category)
    {
        if ($category->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return response()->json($category);
    }

    // Actualizar categoría
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

    // Eliminar categoría
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