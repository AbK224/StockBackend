<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // liste all products

        return Product::all();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // create a new product
       $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'treshold_quantity' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        $product = Product::create($data);
        // ✅ Réponse en cas de succès
        return response()->json([
            'message' => 'Produit ajouté avec succès !',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show a specific product
        return Product::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // update a specific product
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'buying_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'treshold_quantity' => 'sometimes|required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        $product->update($data);
        return response()->json([
            'message' => 'Produit mis à jour avec succès !',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete a specific product
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'message' => 'Produit supprimé avec succès !'
        ], 200);
    }
}
