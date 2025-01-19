<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Customers/Index');
    }

    public function ajaxList(Request $request)
    {
        $query = User::role('Customer');

        if($request->filled('search')){
            $search = $request->query('search');
            $query->where(function($query) use ($search){
               $query->where(DB::raw("CONCAT_WS(' ', users.first_name, users.last_name)"), 'like', "%{$search}%")
                   ->orWhere("users.email", 'like', "%{$search}%");
            });
        }

        if($request->has(['field', 'direction'])) {
            $query->orderBy($request->query('field'), $request->query('direction'));
        }

        return $query->paginate($request->query('size', 10));
    }

    public function fetchHistory($id)
    {
        $histories = LoginHistory::query()->where('user_id', '=', $id)->orderBy('created_at', 'desc')->get();
        return $histories;
    }
}
