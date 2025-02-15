<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::query()->create($request->validated());
        if($user)
        {
            $user->assignRole([2]);
            return redirect()->route('login')->with('success', 'Register Successfully!');
        }else{
            return redirect()->back()->with('error','User registration failed!');
        }
    }
}
