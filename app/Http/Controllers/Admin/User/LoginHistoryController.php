<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Responses\Admin\User\LoginHistoryResponse;
use App\Services\Admin\User\LoginHistory\LoginHistoryDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function __construct(
        public readonly LoginHistoryDataService $dataService,
        public readonly LoginHistoryResponse $response,
    )
    {

    }

    public function index(Request $request, LoginHistory $model): JsonResponse
    {
        $data = $this->dataService->setModel($model)->setRequest($request)->index();
        return $this->response->index($data);
    }
}
