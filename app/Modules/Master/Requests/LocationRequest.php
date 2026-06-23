<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'location_name' => 'required|string|max:150',
            'city_id'       => 'required|integer|exists:sqlsrv.city,city_id',
            'branch_id'     => 'nullable|integer|exists:sqlsrv.branch,branch_id',
            'address'       => 'nullable|string',
            'pincode'       => 'nullable|string|max:20',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'is_active'     => 'required|in:0,1',
        ];
    }
}
