<?php

namespace App\Responses\Admin\Customer;

use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomerResponse
{
    public function index(): Response
    {
        return Inertia::render('Admin/Customer/Index');
    }

    public function ajaxList($data): mixed
    {
        return $data['data'];
    }

    public function detail($data): JsonResponse
    {
        return response()->json(['data' => $data]);
    }

    public function store($result): JsonResponse
    {
        if($result['success']) {
            return response()->json(['message' => $result['message']], 200);
        } else {
            return response()->json(['message' => $result['message']], 401);
        }
    }

    public function update($result): JsonResponse
    {
        if($result['success']) {
            return response()->json(['message' => $result['message']], 200);
        } else {
            return response()->json(['message' => $result['message']], 401);
        }
    }

}
