<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'designation_name' => 'required|string|max:255',
            'status'           => 'required|in:0,1',
            'department_ids'   => 'required|array|min:1',
            'department_ids.*' => 'exists:issueDepartmentMaster,Departmentid',
        ];
    }
}
