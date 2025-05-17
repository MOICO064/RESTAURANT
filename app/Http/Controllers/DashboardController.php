<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\CategoryList;
use App\Models\ProductList;
use App\Models\SaleList;
use Illuminate\Support\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $categoriesCount = CategoryList::where('delete_flag', 0)->where('status', 1)->count();
        $productsCount = ProductList::where('delete_flag', 0)->where('status', 1)->count();

        $user = Auth::user();
        if ($user->hasRole('Cajero')) {
            $salesTotal = SaleList::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');
        } else {
            $salesTotal = SaleList::whereDate('created_at', Carbon::today())
                ->sum('amount');
        }



        $banners = [];
        $bannerPath = public_path('uploads/banner');
        if (File::exists($bannerPath)) {
            foreach (File::files($bannerPath) as $file) {
                $banners[] = asset('uploads/banner/' . $file->getFilename());
            }
        }

        return view('admin.index', compact('categoriesCount', 'productsCount', 'salesTotal', 'banners'));
    }
}

