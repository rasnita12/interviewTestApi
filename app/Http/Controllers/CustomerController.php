<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::role('Customer')->get();
        return Inertia::render('Customers/Index',['users' => $users]);
    }
}
