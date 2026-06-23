<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'branch_name'  => 'required|string|max:150',
            'branch_code'  => ['required', 'string', 'max:20',
                $id
                    ? Rule::unique('sqlsrv.branch', 'branch_code')->ignore($id, 'branch_id')
                    : Rule::unique('sqlsrv.branch', 'branch_code'),
            ],
            'zone_id'      => 'required|integer|exists:sqlsrv.zone,zone_id',
            'city_id'      => 'required|integer|exists:sqlsrv.city,city_id',
            'manager_name' => 'nullable|string|max:100',
            'contact_no'   => 'nullable|string|max:20',
            'is_active'    => 'required|in:0,1',
        ];
    }
}
