<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all products
        $products = Product::with('category')->paginate(10); // charge la relation avec la catégorie et pagine les résultats
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create a new product
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'threshold_quantity' => 'required|integer',
            'expiration_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id', // Foreign key to suppliers table
        ]);
        $product = Product::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Prouit créé avec succès',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $product
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $product = Product::findOrFail($id); // Trouve le produit par son ID

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'produit non trouvé'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'buying_price' => 'sometimes|required|numeric',
            'selling_price' => 'sometimes|required|numeric',
            'stock_quantity' => 'sometimes|required|integer',
            'threshold_quantity' => 'sometimes|required|integer',
            'expiration_date' => 'sometimes|nullable|date',
            'supplier_id' => 'sometimes|nullable|exists:suppliers,id', // Foreign key to suppliers table
        ]);
        $product->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        //
        $product = Product::findOrFail($id); // Trouve le produit par son ID
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'produit non trouvé'
            ], 404);
        }
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ], 200);
    }
}
