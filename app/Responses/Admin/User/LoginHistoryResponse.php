<?php

namespace App\Responses\Admin\User;

use Illuminate\Http\JsonResponse;

class LoginHistoryResponse
{
    public function index($result): JsonResponse
    {
        return response()->json($result);
    }
}
