<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'department_name' => 'required|string|max:255',
            'status'          => 'required',
        ];
    }
}
