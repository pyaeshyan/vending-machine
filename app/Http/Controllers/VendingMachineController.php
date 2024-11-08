<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class VendingMachineController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return view('vanding', compact('products'));
    }
}
