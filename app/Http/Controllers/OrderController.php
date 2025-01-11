<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\userInfo;
use App\Models\MyOrder;
use App\Models\Table;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function walkin()
    {
        return view('admin.order.walkin');
    }

    

}
