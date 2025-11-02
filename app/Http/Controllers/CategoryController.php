<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        // list all categories
        return Category::all();
    }

    public function store (request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category = Category::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'data' => $category
        ], 201);
    }
}
