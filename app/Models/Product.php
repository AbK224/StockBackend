<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'buying_price',
        'selling_price',
        'stock_quantity',
        'threshold_quantity',
        'expiration_date',
        'supplier_id',
    ];
    //
    public function category() // Relation with Category model
    {
        return $this->belongsTo(Category::class); // Each product belongs to one category
    }
}
