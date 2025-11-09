<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
       'product_id',
        'supplier_id',
        'quantity',
        'unit',
        'order_date',
        'expected_delivery_date',
        'status',
        'order_value',
        'received',
    ];
    //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


}
