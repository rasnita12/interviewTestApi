<?php

namespace App\Services\Admin\User\LoginHistory;

use App\Services\BaseService;

class LoginHistoryDataService extends BaseService
{
    public function index()
    {
        return $this->model->query()->where('user_id', '=', $this->id)->orderBy('created_at', 'desc')->get();
    }
}
