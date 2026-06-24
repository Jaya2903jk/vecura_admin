<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'state_code' => 'required|string|max:10',
            'state_name' => 'required|string|max:100',
            'country_id' => 'required|integer|exists:sqlsrv.country,country_id',
            'is_active'  => 'required|in:0,1',
        ];
    }
}
