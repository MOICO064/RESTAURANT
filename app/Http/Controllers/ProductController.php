<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductList;
use App\Models\CategoryList;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductList::with('category_list')->where('delete_flag', 0)->orderBy('name', 'asc')->get();
        return view('admin.productos.index', compact('products'));
    }
    public function form($id = null)
    {
        $categories = CategoryList::where('delete_flag', 0)
            ->where('status', 1)
            ->get();

        $product = null;

        if ($id) {
            $product = ProductList::findOrFail($id);
            return view('admin.productos.manage_products', compact('product', 'categories'));
        }
        return view('admin.productos.manage_products', compact('categories'));
    }

    public function storeOrUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Only AJAX requests are allowed.'
            ], 403);
        }

        $id = $request->input('id');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:category_list,name,' . $id,
            'category_id' => 'required|exists:category_list,id',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = ProductList::updateOrCreate(
                ['id' => $id],
                $validator->validated()
            );

            return response()->json([
                'status' => 'success',
                'message' => $id ? 'Product successfully updated.' : 'New Product successfully saved.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $product = ProductList::with('category_list')->findOrFail($id);

        return view('admin.productos.show', compact('product'));
    }

    public function destroy($id)
    {
        $product = ProductList::findOrFail($id);
        $product->delete_flag = 1;
        $product->save();

        return response()->json(['status' => 'success']);
    }

}
