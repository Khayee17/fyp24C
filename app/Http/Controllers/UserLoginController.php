<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use App\Models\UserInfo; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserLoginController extends Controller
{
    // 显示登录页面
    public function showLoginForm()
    {

        return view('userlogin');
    }

    // 处理用户信息存储
    public function storeUserInfo(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required',
            'numberOfCustomers' => 'required|integer',
        ]);

        // 存储到数据库
        UserInfo::create($validated);

        // 重定向到菜单页面
        return redirect()->route('menu');
    }
    

    
}
