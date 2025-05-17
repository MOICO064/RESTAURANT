<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{


    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $user_id = $request->input('user_id', 0);
        $authUser = auth()->user();

        if ($authUser->hasRole('Cajero')) {
            $user_id = $authUser->id;
        }


        $query = DB::table('sale_list')
            ->whereDate('created_at', $date)
            ->when($user_id > 0, function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->orderByDesc(DB::raw('UNIX_TIMESTAMP(updated_at)'))
            ->get();

        $total = $query->sum('amount');

        $users = DB::table('users')
            ->select('id', 'name')
            ->whereIn('id', $query->pluck('user_id')->unique())
            ->pluck('name', 'id');

        return view('admin.reportes.index', compact('date', 'user_id', 'query', 'users', 'total'));
    }

}
