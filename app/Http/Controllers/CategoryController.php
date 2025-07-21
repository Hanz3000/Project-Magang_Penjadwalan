<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);
        
        $category = Category::create($request->only('name'));
        
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id
        ]);
        
        $category->update($request->only('name'));
        
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        // Cek apakah kategori sedang digunakan
        if ($category->tasks()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena sedang digunakan'
            ], 422);
        }
        
        $category->delete();
        
        return response()->json([
            'success' => true
        ]);
    }
}