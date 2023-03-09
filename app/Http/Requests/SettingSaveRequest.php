<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingSaveRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
        // return auth()->guard(config('api.api_guard'))->check() || auth()->guard(config('api.cms_guard'))->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type'  => 'required|string|min:2|max:255',
            'key'   => 'required|string|min:2|max:255',
            'value' => 'required|string|min:2|max:65535',
        ];
    }
}
