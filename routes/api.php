<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;


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
// Route pour rediriger vers le fournisseur OAuth
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']); // Route pour rediriger vers le fournisseur OAuth
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']); // Route pour gérer le callback OAuth
// Routes pour la gestion des produits
//Route::middleware('auth:sanctum')->group(function () {
Route::get('/products', [ProductController::class, 'index']); // Liste tous les produits
Route::post('/product', [ProductController::class, 'store']); // Crée un nouveau produit
Route::get('/products/{id}', [ProductController::class, 'show']); // Affiche un produit spécifique
Route::put('/products/{id}', [ProductController::class, 'update']); // Met à jour un produit spécifique
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Supprime un produit spécifique
//});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']); // Liste tous les produits
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']); // Crée un nouveau produit
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']); // Affiche un produit spécifique
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']); // Met à jour un produit spécifique
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']); // Supprime un produit spécifique
});


