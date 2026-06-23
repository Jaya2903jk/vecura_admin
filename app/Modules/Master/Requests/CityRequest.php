<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'city_name' => 'required|string|max:100',
            'state_id'  => 'required|integer|exists:sqlsrv.state,state_id',
            'pincode'   => 'nullable|string|max:20',
            'is_active' => 'required|in:0,1',
        ];
    }
}
