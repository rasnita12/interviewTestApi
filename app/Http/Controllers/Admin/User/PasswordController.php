<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PasswordRequest;
use App\Models\User;
use App\Responses\Admin\User\PasswordResponse;
use App\Services\Admin\User\Password\PasswordManipulateService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function __construct(
        public readonly PasswordManipulateService $manipulateService,
        public readonly PasswordResponse $response
    )
    {

    }

    public function update(PasswordRequest $request, $id, User $model): JsonResponse
    {
        $result = $this->manipulateService->setRequest($request)->setModel($model)->setId($id)->changePassword();
        return $this->response->updatePassword($result);
    }
}
