<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function walkin()
    {
        return view('admin.order.walkin');
    }

    

}
