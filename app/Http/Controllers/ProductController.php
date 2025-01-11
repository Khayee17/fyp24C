<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;

class ProductController extends Controller
{
    public function showProduct(Request $request)
    {
        // $products = Product::with('variants')->get();
        $products = Product::with(['variants', 'category'])->paginate(10);
        $categories = Category::all(); // 获取所有类别

        // $products = Product::paginate(10); 
        
        return view('admin.menu.product', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_id' => 'required|string|unique:products',
            'product_des' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'product_img' => 'nullable|image',
            'in_stock' => 'required|boolean',
            'variantName.*' => 'nullable|string|max:255',
            'variantOpt.*' => 'nullable|string',
            'variantPrice.*' => 'nullable|string',
            'is_required.*' => 'nullable|boolean', 
        ]);

       // 处理图片上传
       $productImgPath = null;
       if ($request->hasFile('product_img')) {
           $productImgPath = $request->file('product_img')->store('products', 'public');
       }

       $product = Product::create([
            'product_name' => $request->product_name,
            'product_id' => $request->product_id,
            'product_des' => $request->product_des,
            'unit_price' => $request->unit_price,
            'category_id' => $request->category_id,
            'product_img' => $productImgPath,
            'in_stock' => $request->in_stock,
        ]);

        if ($request->has('variantName')) {
            foreach ($request->variantName as $index => $name) {
                if ($name) { 
                    Variant::create([
                        'product_id' => $product->id,
                        'variantName' => $name,
                        'variantOpt' => $request->variantOpt[$index] ?? '',
                        'variantPrice' => $request->variantPrice[$index] ?? null,
                        'is_required' => $request->is_required[$index] ?? false,
                    ]);
                }
            }
        }

        return redirect()->route('productsShow')->with('success', 'Product "' . $product->product_name . '" added successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all(); // 确保获取所有类别
        
        return view('admin.menu.product', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_id' => 'required|string|unique:products,product_id,' . $id,
            'product_des' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'product_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'in_stock' => 'required|boolean',
            'variantName.*' => 'nullable|string|max:255',
            'variantOpt.*' => 'nullable|string',
            'variantPrice.*' => 'nullable|string',
            'is_required.*' => 'nullable|boolean',  
        ]);

        $product = Product::findOrFail($id);

        // 处理图片上传
        if ($request->hasFile('product_img')) {
            if ($product->product_img) {
                Storage::disk('public')->delete($product->product_img);
            }
            $product->product_img = $request->file('product_img')->store('products', 'public');
        }

        // 更新产品
        $product->update([
            'product_name' => $request->product_name,
            'product_id' => $request->product_id,
            'product_des' => $request->product_des,
            'unit_price' => $request->unit_price,
            'category_id' => $request->category_id,
            'product_img' => $product->product_img,
            'in_stock' => $request->in_stock,
        ]);

        // 更新变体
        $variants = [];
        if ($request->has('variantName')) {
            foreach ($request->variantName as $index => $name) {
                if ($name) {
                    $variants[] = [
                        'variantName' => $name,
                        'variantOpt' => $request->variantOpt[$index] ?? '',
                        'variantPrice' => $request->variantPrice[$index] ?? null,
                        'is_required' => isset($request->is_required[$index]) ? true : false,
                    ];
                }
            }
        }

        $product->variants()->delete(); // 删除旧的变体
        $product->variants()->createMany($variants); // 插入新的变体


        return redirect()->route('productsShow')->with('success', 'Product "' . $product->product_name . '" updated successfully.');
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->in_stock = $request->input('in_stock');
        $product->save();

        return response()->json(['success' => true]);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->product_img) {
            Storage::disk('public')->delete($product->product_img);
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('productsShow')->with('success', 'Product "' . $product->product_name . '" deleted successfully.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        
        $deletedCount = Product::whereIn('id', $ids)->delete();

        session()->flash('success', "$deletedCount products deleted successfully.");

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');

    $products = Product::with('category')
        ->where(function($query) use ($search) {
            $query->where('product_id', 'like', '%' . $search . '%')
                  ->orWhere('product_name', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($query) use ($search) {
                      $query->where('name', 'like', '%' . $search . '%');
                  });
        })
        ->when($category, function($query) use ($category) {
            $query->whereHas('category', function($query) use ($category) {
                $query->where('name', $category);
            });
        })
        ->get();

    return response()->json(['products' => $products]);
}
    


    //USER USER USER USER USER USER
    public function showProductMenu()
   {
       // 获取所有产品及其相关变体
       $products = Product::with('variants')->where('in_stock', 1)->get();
        
       return view('layout', compact('products'));
   }

}
