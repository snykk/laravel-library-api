<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingGetRequest extends FormRequest
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
            'filter.id'                   => 'integer|between:0,18446744073709551615',
            'filter.type'                 => 'string|min:2|max:255',
            'filter.key'                  => 'string|min:2|max:255',
            'filter.value'                => 'string|min:2|max:65535',
            'filter.deleted_at'           => 'date',
            'filter.created_at'           => 'date',
            'filter.updated_at'           => 'date',
            'filter.settings\.id'         => 'integer|between:0,18446744073709551615',
            'filter.settings\.type'       => 'string|min:2|max:255',
            'filter.settings\.key'        => 'string|min:2|max:255',
            'filter.settings\.value'      => 'string|min:2|max:65535',
            'filter.settings\.deleted_at' => 'date',
            'filter.settings\.created_at' => 'date',
            'filter.settings\.updated_at' => 'date',
            'page.number'                 => 'integer|min:1',
            'page.size'                   => 'integer|between:1,100',
            'search'                      => 'nullable|string|min:3|max:60',
        ];
    }
}
