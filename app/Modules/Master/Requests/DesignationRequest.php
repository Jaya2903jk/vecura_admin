<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $designationId = $this->route('id');
        $uniqueRule = 'required|string|max:255|unique:DesignationMaster,Designation';

        // For UPDATE requests, allow the same name for the current designation
        if ($designationId) {
            $uniqueRule .= ',' . $designationId . ',id';
        }

        return [
            'designation_name' => $uniqueRule,
            'status'           => 'required|in:0,1',
            'department_ids'   => 'nullable|array',
            'department_ids.*' => 'exists:issueDepartmentMaster,Departmentid',
        ];
    }

    public function messages(): array
    {
        return [
            'designation_name.unique' => 'This designation name already exists. Please use a different name.',
            'designation_name.required' => 'Designation name is required.',
            'status.required' => 'Status is required.',
            'department_ids.array' => 'Departments must be an array.',
            'department_ids.*.exists' => 'One or more selected departments do not exist.',
        ];
    }
}
