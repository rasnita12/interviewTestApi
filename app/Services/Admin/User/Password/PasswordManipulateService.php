<?php

namespace App\Services\Admin\User\Password;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordManipulateService extends BaseService
{
    public function changePassword(): array
    {
        DB::beginTransaction();
        try {
            $this->model->query()->where('id','=', $this->id)->firstOrFail()
                ->update(['password' => Hash::make($this->request->password)]);
            DB::commit();
            return ['success' => true, 'message' => 'Password Changed Successfully.'];
        } catch (\Throwable $exception) {
            DB::rollBack();
            return ['success' => false, 'message' =>  app()->isProduction() ? 'Failed to Change Password.' : $exception->getMessage()];
        }
    }
}
