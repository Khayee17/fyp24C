<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use App\Models\UserInfo; 
use App\Models\MyOrder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserLoginController extends Controller
{
    // 显示登录页面
    public function showLoginForm()
    {

        return view('userlogin');
    }

    // 处理用户信息存储
    public function saveUserInfo(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'numberOfCustomers' => 'required|integer|min:1',
        ]);
    
        // Save user info in the database
        $userInfo = UserInfo::create([
            'phone' => $validatedData['phone'],
            'numberOfCustomers' => $validatedData['numberOfCustomers'],
        ]);
    
        // Store in session
        session([
            'phone' => $validatedData['phone'],
            'numberOfCustomers' => $validatedData['numberOfCustomers'],
        ]);
       
        return redirect()->route('menu');
    }
    

    
}
