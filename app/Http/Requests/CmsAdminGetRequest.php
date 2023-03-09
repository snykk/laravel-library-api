<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CmsAdminGetRequest extends FormRequest
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
            'filter.id'                         => 'integer|between:0,18446744073709551615',
            'filter.name'                       => 'string|min:2|max:255',
            'filter.email'                      => 'string|email|min:11|max:255',
            'filter.password'                   => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.remember_token'             => 'string|min:2|max:100',
            'filter.deleted_at'                 => 'date',
            'filter.created_at'                 => 'date',
            'filter.updated_at'                 => 'date',
            'filter.cms_admins\.id'             => 'integer|between:0,18446744073709551615',
            'filter.cms_admins\.name'           => 'string|min:2|max:255',
            'filter.cms_admins\.email'          => 'string|email|min:11|max:255',
            'filter.cms_admins\.password'       => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.cms_admins\.remember_token' => 'string|min:2|max:100',
            'filter.cms_admins\.deleted_at'     => 'date',
            'filter.cms_admins\.created_at'     => 'date',
            'filter.cms_admins\.updated_at'     => 'date',
            'page.number'                       => 'integer|min:1',
            'page.size'                         => 'integer|between:1,100',
            'search'                            => 'nullable|string|min:3|max:60',
        ];
    }
}
