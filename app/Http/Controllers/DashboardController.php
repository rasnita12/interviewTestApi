<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $data['customers'] = User::role('Customer')->count();
        $data['healthCenters'] = HealthCenter::query()->count();
        return Inertia::render('Dashboard/Index', $data);
    }
}
