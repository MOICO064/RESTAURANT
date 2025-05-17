<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleList;
use App\Models\ProductList;
use App\Models\CategoryList;
use App\Models\SaleProduct;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class SalesController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('Cajero')) {
            $sales = SaleList::where('user_id', Auth::id())
                ->orderByDesc('updated_at')
                ->get();
        } else {
            $sales = SaleList::orderByDesc('updated_at')->get();
        }        

        return view('admin.ventas.index', compact('sales'));
    }

    public function form($id = null)
    {
        $sale = $id ? SaleList::with('products.product')->findOrFail($id) : null;
        $categories = CategoryList::where('delete_flag', 0)->where('status', 1)->orderBy('name')->get();
        $products = ProductList::where('delete_flag', 0)->where('status', 1)->orderBy('name')->get()->groupBy('category_id');

        return view('admin.ventas.manage_ventas', compact('sale', 'categories', 'products'));
    }
    public function saveSale(Request $request)
    {
        try {
            DB::beginTransaction();

            $sale = isset($request->id) ? SaleList::find($request->id) : new SaleList();

            if (!$sale->exists) {
                $prefix = now()->format('Ymd');
                $code = 1;

                do {
                    $saleCode = $prefix . str_pad($code, 4, '0', STR_PAD_LEFT);
                    $exists = SaleList::where('code', $saleCode)->exists();
                    $code++;
                } while ($exists);

                $sale->code = $saleCode;
                $sale->user_id =  auth()->id();
            }

            // Asignar solo los campos permitidos (fillable)
            $sale->fill($request->only($sale->getFillable()));

            $sale->save();

            if ($request->id) {
                SaleProduct::where('sale_id', $sale->id)->delete();
            }

            $products = [];
            foreach ($request->product_id as $i => $productId) {
                $products[] = new SaleProduct([
                    'product_id' => $productId,
                    'qty' => $request->product_qty[$i],
                    'price' => $request->product_price[$i],
                ]);
            }

            $sale->products()->saveMany($products);

            DB::commit();
            session()->flash('success', $request->id ? 'Venta actualizada correctamente.' : 'Venta registrada exitosamente.');
            return response()->json([
                'status' => 'success',
                'msg' => $request->id ? 'Sale successfully updated.' : 'New Sale successfully saved.',
                'sid' => $sale->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'msg' => 'Sale transaction failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show($id)
    {
        $sale = SaleList::with(['products.product', 'user'])->find($id);

        if (!$sale) {
            return redirect()->route('admin.sales.index')->with('error', "Unknown sale's ID.");
        }

        return view('admin.ventas.show', compact('sale'));
    }


    public function destroy(Request $request)
    {
        $id = $request->input('id');

        try {
            $sale = SaleList::find($id);

            if (!$sale) {
                return response()->json([
                    'status' => 'failed',
                    'error' => 'Sale not found.'
                ]);
            }

            $sale->delete();


            session()->flash('success', 'Sale successfully deleted.');

            return response()->json([
                'status' => 'success',
                'message' => 'Sale successfully deleted.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
        }
    }

}
