<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'buying_price',
        'selling_price',
        'stock_quantity',
        'treshold_quantity',
        'supplier_id'
    ];
    public function category()
    {

        return $this->belongsTo(Category::class); // Un produit appartient à une catégorie

    }

}
