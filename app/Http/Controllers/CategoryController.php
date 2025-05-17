<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryList;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryList::where('delete_flag', 0)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.categorias.index', compact('categories'));
    }
    public function form($id = null)
    {
        $category = null;

        if ($id) {
            $category = CategoryList::findOrFail($id);
            return view('admin.categorias.manage_category', compact('category'));
        }
        return view('admin.categorias.manage_category');
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
            'description' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = CategoryList::updateOrCreate(
                ['id' => $id],
                $validator->validated()
            );

            return response()->json([
                'status' => 'success',
                'message' => $id ? 'Category successfully updated.' : 'New Category successfully saved.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function view($id)
    {
        $categoria = CategoryList::findOrFail($id);
        return view('admin.categorias.show', compact('categoria'));
    }

    public function destroy($id)
    {
        $category = CategoryList::findOrFail($id);
        $category->delete_flag = 1;
        $category->save();

        return response()->json(['status' => 'success', 'message' => 'Category deleted successfully.']);
    }


}

