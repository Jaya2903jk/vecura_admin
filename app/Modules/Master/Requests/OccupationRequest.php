<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccupationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'occupation_name' => 'required|string|max:100',
            'occupationType' => 'nullable|string|max:100',
        ];
    }
}
