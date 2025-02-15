<?php

namespace App\Services\Admin\Customer;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CustomerManipulateService extends BaseService
{
    public function store(): array
    {
        DB::beginTransaction();
        try {
            $data = $this->request->validated();
            $user = $this->model->query()->create($data);

            if($user)
            {
                $user->assignRole([2]);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Customer Added Successfully.'];
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return ['success' => false, 'message' =>  app()->isProduction() ? 'Failed to Add Customer.' : $throwable->getMessage()];
        }
    }

    public function update(): array
    {
        $user = $this->model->query()->role('Customer')->findOrFail($this->id);

        DB::beginTransaction();
        try {
            $data = $this->request->validated();
            $user->update($data);

            DB::commit();
            return ['success' => true, 'message' => 'Customer Updated Successfully.'];
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return ['success' => false, 'message' =>  app()->isProduction() ? 'Failed to Update Customer.' : $throwable->getMessage()];
        }
    }
}
