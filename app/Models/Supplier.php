<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'takes_back_returns'
    ];
    //
    public function products() // Relation with Product model
    {
        return $this->hasMany(Product::class);
    }

    
}
