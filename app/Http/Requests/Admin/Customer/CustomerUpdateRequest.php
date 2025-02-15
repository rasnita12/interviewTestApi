<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'required|string|min:3',
            'last_name'=>'required|string|min:3',
            'email'=>'nullable|email|unique:users'.(request()->method() == 'PATCH' ? ',email,' . $this->id : ''),
            'username'=>'required|alpha_dash|unique:users'.(request()->method() == 'PATCH' ? ',username,' . $this->id : ''),
        ];
    }
}
