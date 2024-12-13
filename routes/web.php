<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** for side bar menu active */
// Ensure the function is not redeclared
if (!function_exists('set_active')) {
    function set_active($route) {
        if (is_array($route)) {
            return in_array(Request::path(), $route) ? 'active' : '';
        }
        return Request::path() == $route ? 'active' : '';
    }
}


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/order', function () {
//     return view('layout');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 我的
Route::get('/amdashboard', function () {
    return view('admin.dashboard.admindashboard');
});

// Route::get('/category', function () {
//     return view('admin.menu.category');
// });

// Route::get('/product', function () {
//     return view('admin.menu.product');
// });

Route::get('/waitlist', function () {
    return view('admin.order.waitlist');
});

Route::get('/walkin', function () {
    return view('admin.order.walkin');
});

//category
// 显示类别列表
Route::get('/categories', [CategoryController::class, 'showCtg'])->name('categoriesShow');

// 分类搜索请求
Route::get('/categories/search', [CategoryController::class, 'searchCat'])->name('categoriesSearch');

// 显示创建类别的表单
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categoriesCreate');

Route::get('/check-unique', [CategoryController::class, 'checkUnique'])->name('categories.checkUnique');

// 存储新类别
Route::post('/categories', [CategoryController::class, 'storeCtg'])->name('categorieStore');

// 显示编辑类别的表单
Route::get('/categories/{category}/edit', [CategoryController::class, 'editCtg'])->name('categoriesEdit');

// 更新类别
Route::put('/categories/{category}', [CategoryController::class, 'updateCtg'])->name('categoriesUpdate');

// 删除类别
Route::delete('/categories/{category}', [CategoryController::class, 'deleteCtg'])->name('categoriesDelete');

Route::post('/categories/delete-multiple', [CategoryController::class, 'deleteMultiple']);

Route::post('/categories/search', [CategoryController::class, 'search'])->name('categories.search');

//USER USER USER USER USER USER
Route::get('/order', [CategoryController::class, 'showMenu']);





//PRODUCT PRODUCT PRODUCT PRODUCT PRODUCT PRODUCT
Route::get('/products', [ProductController::class, 'showProduct'])->name('productsShow');

// 存储新产品
Route::post('/products', [ProductController::class, 'storeProduct'])->name('productsStore');

// 编辑产品页面
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('productsEdit');

// 更新产品
Route::put('/products/{id}', [ProductController::class, 'update'])->name('productsUpdate');

Route::post('/products/{id}/update-stock', [ProductController::class, 'updateStock']);

// 删除产品
Route::delete('/products/{id}', [ProductController::class, 'deleteProduct'])->name('productsDelete');

// 显示产品菜单
// Route::get('/order', [ProductController::class, 'showProductMenu'])->name('productMenu');
Route::post('/products/delete-multiple', [ProductController::class, 'deleteMultiple']);

Route::post('/products/search', [ProductController::class, 'search'])->name('products.search');



//cart
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
