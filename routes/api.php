<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\SupplierController;


/* Route::get('/ping', function () {
    return response()->json(['ping' => 'pong']);
}); 
*/

Route::post('/register', [AuthController::class, 'register']); // Route pour l'inscription
Route::post('/login', [AuthController::class, 'login']);// Route pour la connexion
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');// Route pour la déconnexion, protégée par le middleware auth:sanctum
// Route protégée pour obtenir les informations de l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) { 
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    // pour les produits
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']); // Liste tous les produits
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']); // Crée un nouveau produit
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']); // Affiche un produit spécifique
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']); // Met à jour un produit spécifique
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']); // Supprime un produit spécifique
    // pour les categories
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index']); // Liste toutes les catégories
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store']); // Crée une nouvelle catégorie
    // pour les fournisseurs
    Route::get('/suppliers', [SupplierController::class, 'index']); // Liste tous les fournisseurs
    Route::get('/suppliers/{id}', [SupplierController::class, 'show']); // Affiche un fournisseur spécifique
    Route::post('/suppliers', [SupplierController::class, 'store']); // Crée un nouveau fournisseur
    Route::put('/suppliers/{id}', [SupplierController::class, 'update']);// Met à jour un fournisseur existant
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']); // Supprime un fournisseur

    // pour les commandes d'achat
    Route::get('/orders', [App\Http\Controllers\PurchaseOrderController::class, 'index']); // Liste toutes les commandes d'achat
    Route::post('/orders', [App\Http\Controllers\PurchaseOrderController::class, 'store']); // Crée une nouvelle commande d'achat
    Route::get('/orders/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'show']); // Affiche une commande d'achat spécifique
    Route::put('/orders/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'update']); // Met à jour une commande d'achat spécifique
    Route::delete('/orders/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'destroy']); // Supprime une commande d'achat
});


