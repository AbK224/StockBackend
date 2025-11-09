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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('unit')->default('pieces');
            $table->timestamp('order_date')->useCurrent();
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'received', 'returned', 'delayed', 'OutforDelivery' ])->default('pending');
            $table->decimal('order_value', 10, 2);
            $table->boolean('received')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
