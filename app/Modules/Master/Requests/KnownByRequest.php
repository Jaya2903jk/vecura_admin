<?php

namespace App\Modules\Master\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnownByRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'KnwCode' => 'nullable|string|max:50',
            'KwnBy' => 'required|string|max:100',
            'kstatus' => 'nullable|string|in:Active,Inactive',
            'digital' => 'nullable|string|max:100',
        ];
    }
}
