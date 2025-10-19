<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    public function products() // Relation with Product model
    {
        return $this->hasMany(Product::class);
    }
    
}
