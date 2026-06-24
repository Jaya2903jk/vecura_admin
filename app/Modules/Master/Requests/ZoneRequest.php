<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'zone_code'   => 'required|string|max:20',
            'zone_name'   => 'required|string|max:100',
            'country_id'  => 'required|integer|exists:sqlsrv.country,country_id',
            'region_type' => 'nullable|string|max:50',
            'is_active'   => 'required',
        ];
    }
}
