<?php

namespace App\Responses\Admin\User;

use Illuminate\Http\JsonResponse;

class PasswordResponse
{
    public function updatePassword($result): JsonResponse
    {
        if($result['success']) {
            return response()->json(['message' => $result['message']], 200);
        } else {
            return response()->json(['message' => $result['message']], 401);
        }
    }
}
