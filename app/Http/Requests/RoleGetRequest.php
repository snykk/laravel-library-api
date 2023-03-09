<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleGetRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
//        return auth()->guard('api')->check() || auth()->guard('cms-api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter.id'                => 'integer|between:0,18446744073709551615',
            'filter.name'              => 'string|min:2|max:255',
            'filter.guard_name'        => 'string|min:2|max:255',
            'filter.created_at'        => 'date',
            'filter.updated_at'        => 'date',
            'filter.roles\.id'         => 'integer|between:0,18446744073709551615',
            'filter.roles\.name'       => 'string|min:2|max:255',
            'filter.roles\.guard_name' => 'string|min:2|max:255',
            'filter.roles\.created_at' => 'date',
            'filter.roles\.updated_at' => 'date',
            'page.number'              => 'integer|min:1',
            'page.size'                => 'integer|between:1,100',
            'search'                   => 'nullable|string|min:3|max:60',
        ];
    }
}
