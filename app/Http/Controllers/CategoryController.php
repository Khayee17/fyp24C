<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function showCtg(Request $request)
    {
        // 获取所有分类
        $categories = Category::all();
        
        $categories = Category::paginate(10); // 每页显示10条
        
        // 返回视图并传递所有分类
        return view('admin.menu.category', compact('categories'));
    }

    public function searchCat(Request $request)
    {
        // 获取搜索条件
        $searchCategory = $request->input('category');
        $searchCategoryId = $request->input('category_id');

        // 根据搜索条件过滤分类
        $categories = Category::query()
            ->when($searchCategory, fn($query) => $query->where('name', 'like', '%' . $searchCategory . '%'))
            ->when($searchCategoryId, fn($query) => $query->where('category_id', 'like', '%' . $searchCategoryId . '%'))
            ->paginate(10); // 每页显示 10 条分类数据

        // 返回部分视图
        return view('admin.menu.category', compact('categories'));
    }


    public function storeCtg(Request $request)
    {
    // 获取输入的 category name 和 category id
    $categoryName = $request->name;
    $categoryId = $request->category_id;

    // 检查 Category Name 和 Category ID 组合是否同时已存在
    $existingCategory = Category::where('name', $categoryName)
                                    ->where('category_id', $categoryId)
                                    ->first();

    if ($existingCategory) {
        return redirect()->back()->with('error', 'Category Name and ID combination already exists.');
    }

    // 检查单独的 Category Name 是否已存在
    $existingCategoryName = Category::where('name', $categoryName)->first();
    if ($existingCategoryName) {
        return redirect()->back()->with('error', 'Category Name already exists.');
    }

    // 检查单独的 Category ID 是否已存在
    $existingCategoryId = Category::where('category_id', $categoryId)->first();
    if ($existingCategoryId) {
        return redirect()->back()->with('error', 'Category ID already exists.');
    }

        // 验证表单数据
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'category_id' => 'required|string|max:255|unique:categories,category_id',
            'ctgImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 处理图片上传
        $ctgImgPath = null;
        if ($request->hasFile('ctgImg')) {
            $ctgImgPath = $request->file('ctgImg')->store('categories', 'public');
        }

        // 保存新类别
        $category = Category::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'ctgImg' => $ctgImgPath,
        ]);

        return redirect()->route('categoriesShow')->with('success', 'Category "' . $category->name . '" added successfully.');
    }


    // 显示编辑类别的表单
    public function editCtg($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.menu.category', compact('category'));
    }

    public function updateCtg(Request $request, $id)
    {

        // 数据验证
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|string|max:255|unique:categories,category_id,' . $id, // 忽略当前记录的 ID
            'ctgImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 查找并获取要更新的类别
        $category = Category::findOrFail($id);

        // 如果有上传新的图片，处理图片上传和删除旧图片
        if ($request->hasFile('ctgImg')) {
            // 删除旧照片（如果存在）
            if ($category->ctgImg) {
                Storage::disk('public')->delete($category->ctgImg);
            }
            
            // 保存新图片并更新文件路径
            $category->ctgImg = $request->file('ctgImg')->store('categories', 'public');
        }

        // 更新类别数据，包括名字和图片路径
        $category->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'ctgImg' => $category->ctgImg, // 确保图片路径被更新
        ]);

        // 重定向到类别列表页面并显示成功消息
        return redirect()->route('categoriesShow')->with('success', 'Category "' . $category->name . '" updated successfully.');
    }

    // 删除类别
    public function deleteCtg($id)
    {
        $category = Category::findOrFail($id);

        // 删除照片
        if ($category->ctgImg) {
            Storage::disk('public')->delete($category->ctgImg);
        }

        $category->delete();

        return redirect()->route('categoriesShow')->with('success', 'Category "' . $category->name . '" deleted successfully.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        $deletedCount = Category::whereIn('id', $ids)->delete();

        session()->flash('success', "$deletedCount categories deleted successfully.");

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');

    $categories = Category::where(function($query) use ($search) {
            $query->where('category_id', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%');
        })
        ->when($category, function($query) use ($category) {
            $query->where('name', $category);
        })
        ->get();

    return response()->json(['categories' => $categories]);
}

    //USER USER USER USER USER USER
    public function showMenu()
   {
        $categories = Category::with('products.variants')->get(); // 确保加载关联数据

        $products = Product::all();
        
        // 将数据传递给视图
        return view('layout', compact('categories', 'products'));
   }

   

    
}
