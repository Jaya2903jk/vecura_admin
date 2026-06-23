<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'country_code' => ['required', 'string', 'max:2',
                $id
                    ? Rule::unique('sqlsrv.country', 'country_code')->ignore($id, 'country_id')
                    : 'unique:sqlsrv.country,country_code',
            ],
            'country_name' => 'required|string|max:100',
            'is_active'    => 'required|in:0,1',
        ];
    }
}
