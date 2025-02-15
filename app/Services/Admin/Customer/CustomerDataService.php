<?php

namespace App\Services\Admin\Customer;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CustomerDataService extends BaseService
{
    public function index(): array
    {
        $query = $this->model->query()->role('Customer');

        if($this->request->filled('search')){
            $search = $this->request->query('search');
            $query->where(function($query) use ($search){
                $query->where(DB::raw("CONCAT_WS(' ', users.first_name, users.last_name)"), 'like', "%{$search}%")
                    ->orWhere("users.email", 'like', "%{$search}%");
            });
        }

        if($this->request->has(['field', 'direction'])) {
            $query->orderBy($this->request->query('field'), $this->request->query('direction'));
        }

        return ['data' => $query->paginate($this->request->query('size', 10))];
    }

    public function show()
    {
        return $this->model->query()->role('Customer')->findOrFail($this->id);
    }
}
