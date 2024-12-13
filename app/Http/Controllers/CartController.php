<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = $request->input('product');
        $cart = session()->get('cart', []);
        
        $productId = $product['id'];
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $product['quantity'];
        } else {
            $cart[$productId] = $product;
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

}
