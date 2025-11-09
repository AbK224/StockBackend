<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //list all purchase orders
       $orders = PurchaseOrder::with('product.category', 'supplier') // charge les relations avec le produit (et sa catégorie) et le fournisseur
            ->orderBy('order_date', 'desc')  // trie par date de commande décroissante
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'nullable|date',
            'expected_delivery_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($request->product_id);
        $supplier = Supplier::findOrFail($request->supplier_id);

        // 🔸 Optionnel : Vérifier cohérence produit/fournisseur
        if ($product->supplier_id && $product->supplier_id !== $supplier->id) {
            // Si incohérent, tu peux soit bloquer, soit juste avertir :
            return response()->json([
                'message' => "Le produit sélectionné n'appartient pas à ce fournisseur."
            ], 422);
        }

        $orderValue = $product->buying_price * $request->quantity;
        // Créer la commande d'achat 
        $order = PurchaseOrder::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'quantity' => $request->quantity,
            'unit' => $request->unit ?? 'pcs',
            'order_date' => $request->order_date ?? now(),
            'expected_delivery_date' => $request->expected_delivery_date,
            'status' => 'Confirmed', // statut initial
            'order_value' => $orderValue,
            'received' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->load('product.category', 'supplier'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // show a specific purchase order
        $order = PurchaseOrder::with('product.category', 'supplier')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,received,delayed,returned',
            'expected_delivery_date' => 'nullable|date',
            'quantity' => 'nullable|integer|min:1',
            
        ]);

        $order = PurchaseOrder::with('product')->findOrFail($id);
       

        DB::beginTransaction(); // Commencer une transaction

        try {
            // mettre à jour le statut et la date prévue si fourni
            $order->update([
                'status' => $request->status ?? $order->status, // conserver l'ancien statut si non fourni
                'expected_delivery_date' => $request->expected_delivery_date ?? $order->expected_delivery_date, // conserver l'ancienne date si non fournie
                'quantity' => $request->quantity ?? $order->quantity,
            ]);

            // 🟢 Si la commande passe à "Received" et n’était pas encore reçue :
            if ($request->status === 'received' && !$order->received) {
                $product = $order->product;

                  // Incrmenter la quantite du produit
                $product->stock_quantity += $order->quantity;
                $product->save();

                // Marquer la commande comme reçue
                $order->update(['received' => true]);
            }

            DB::commit(); // Valider la transaction

            return response()->json([
                'success' => true,
                'message' => "Commande mise à jour avec succès.",
                'data' => $order->load('product.category', 'supplier'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la mise à jour : " . $e->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
         $order = PurchaseOrder::findOrFail($id);

        if ($order->status === 'delivered') {
            return response()->json([
                'message' => "Impossible de supprimer une commande déjà livrée."
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => "Commande supprimée avec succès."
        ]);
    }
}
