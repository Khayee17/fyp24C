<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $product = $request->input('product');

        if (isset($cart[$productId])) {
            // 如果商品已经在购物车中，增加数量
            $cart[$productId]['quantity']++;
        } else {
            // 否则新增商品
            $cart[$productId] = [
                'product_id' => $productId,
                'product_name' => $product['product_name'],
                'product_img' => $product['product_img'],
                'unit_price' => $product['unit_price'],
                'quantity' => 1,
                'remark' => '', // 初始备注为空
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart',
            'cart_count' => count($cart),
        ]);
    }



    public function getCart()
    {
        $cart = session()->get('cart', []);
        return response()->json($cart);
    }




}
