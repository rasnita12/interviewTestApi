<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerRequest;
use App\Http\Requests\Admin\Customer\CustomerUpdateRequest;
use App\Models\User;
use App\Responses\Admin\Customer\CustomerResponse;
use App\Services\Admin\Customer\CustomerDataService;
use App\Services\Admin\Customer\CustomerManipulateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        public readonly CustomerResponse $response,
        public readonly CustomerDataService $dataService,
        public readonly CustomerManipulateService $manipulateService
    )
    {

    }

    public function index(): \Inertia\Response
    {
        return $this->response->index();
    }

    public function ajaxLists(Request $request, User $model): mixed
    {
        $data = $this->dataService->setRequest($request)->setModel($model)->index();
        return $this->response->ajaxList($data);
    }

    public function detail($id, User $model): JsonResponse
    {
        $data = $this->dataService->setId($id)->setModel($model)->show();
        return $this->response->detail($data);
    }

    public function store(CustomerRequest $request, User $model): JsonResponse
    {
        $result = $this->manipulateService->setModel($model)->setRequest($request)->store();
        return $this->response->store($result);
    }

    public function update(CustomerUpdateRequest $request, $id, User $model): JsonResponse
    {
        $result = $this->manipulateService->setRequest($request)->setModel($model)->setId($id)->update();
        return $this->response->update($result);
    }
}
