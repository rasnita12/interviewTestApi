<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

abstract class BaseService
{
    public $request;
    public $validator;
    public $model;
    public $id;

    public function __construct()
    {

    }

    public function setRequest($request): static
    {
        $this->request = $request;
        return $this;
    }

    public function setValidator($validator): static
    {
        $this->validator = $validator;
        return $this;
    }

    public function setModel($model): static
    {
        $this->model = $model;
        return $this;
    }

    public function cacheThis($key, $callback)
    {
        return Cache::rememberForever($key, $callback);
    }

    public function setId($id): static
    {
        $this->id = $id;
        return $this;
    }
}
