<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $data['setting'] = DB::table('settings')->first();
        return Inertia::render('Settings/index',$data);
    }

    public function store(Request $request)
    {
        DB::table('settings')->where('id','=','1')->update([
            'map_api_key' => $request->map_api_key,
            'map_id' => $request->map_id
        ]);
        return redirect()->back();
    }
}
