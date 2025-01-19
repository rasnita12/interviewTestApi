<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Throwable;

class UserAccountController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index');
    }

}
