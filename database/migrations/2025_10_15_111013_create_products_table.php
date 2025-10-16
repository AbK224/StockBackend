<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Clé étrangère vers la table categories
            $table->decimal('buying_price', 10, 2);
            $table->decimal('selling_price',10, 2);
            $table->integer('stock_quantity');
            $table->integer('treshold_quantity'); // Seuil de réapprovisionnement
            $table->date('expiration_date')->nullable(); // Date d'expiration, nullable si non périssable
            $table->foreignId('supplier_id')->nullable(); // Clé étrangère vers la table suppliers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
