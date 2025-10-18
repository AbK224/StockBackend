<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;





class ProductController extends Controller
{
    
  /*   public function __construct()
    {
        // Appliquer le middleware d'authentification à toutes les méthodes de ce contrôleur
        $this->middleware('auth:sanctum');
    }   */ 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // liste all products

        return response()->json(Product::all(), 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // create a new product
         try { // Valide les données entrantes
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

            return response()->json([
                'message' => '✅ Produit ajouté avec succès !',
                'product' => $product
            ], 201);

        } catch (ValidationException $e) {
            // ⚠️ Retourne les erreurs de validation détaillées
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
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
