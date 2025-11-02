<?php

namespace App\Http\Controllers;
use App\Models\Supplier;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{

    public function index()
    {
    // liste de tous les fournisseurs
    // charger les produits associés :

        $suppliers = Supplier::with('products')->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers
        ], 200);
    }

    /**
     *  Affiche un fournisseur spécifique (avec ses produits)
     */
    public function show($id)
    {
        $supplier = Supplier::with('products')->find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Fournisseur introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $supplier
        ], 200);
    }

    /**
     * Crée un nouveau fournisseur
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'takes_back_returns' => 'required|boolean'
        ]);

        // Création du fournisseur
        $supplier = Supplier::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Fournisseur créé avec succès.',
            'data' => $supplier
        ], 201);
    }

    /**
     * Mettre à jour un fournisseur existant
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Fournisseur introuvable.'
            ], 404);
        }

        // Validation
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes', 'required', 'email',
                Rule::unique('suppliers')->ignore($supplier->id) // Ignore l'email actuel du fournisseur
            ],
            'phone' => 'sometimes|required|string|max:20',
            'takes_back_returns' => 'sometimes|required|boolean'
        ]);

        // Mise à jour
        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Fournisseur mis à jour avec succès.',
            'data' => $supplier
        ], 200);
    }

    /**
     * Supprime un fournisseur
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Fournisseur introuvable.'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fournisseur supprimé avec succès.'
        ], 200);
    }
}
