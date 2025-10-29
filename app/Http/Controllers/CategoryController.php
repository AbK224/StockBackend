<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        // list all categories
        return Category::all();
    }
}
