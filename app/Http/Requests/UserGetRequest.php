<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserGetRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
//        return (auth()->guard('api')->check() || auth()->guard('cms-api')->check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter.id' => 'integer|between:0,18446744073709551615',
            'filter.name' => 'string|min:2|max:255',
            'filter.email' => 'string|email|min:11|max:255',
            'filter.email_verified_at' => 'date',
            'filter.password' => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.reviews' => 'integer|between:-2147483647,2147483647',
            'filter.remember_token' => 'string|min:2|max:100',
            'filter.created_at' => 'date',
            'filter.updated_at' => 'date',
            'filter.users\.id' => 'integer|between:0,18446744073709551615',
            'filter.users\.name' => 'string|min:2|max:255',
            'filter.users\.email' => 'string|email|min:11|max:255',
            'filter.users\.email_verified_at' => 'date',
            'filter.users\.password' => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.users\.reviews' => 'integer|between:-2147483647,2147483647',
            'filter.users\.remember_token' => 'string|min:2|max:100',
            'filter.users\.created_at' => 'date',
            'filter.users\.updated_at' => 'date',
            'page.number' => 'integer|min:1',
            'page.size' => 'integer|between:1,100',
            'search' => 'nullable|string|min:3|max:60',
        ];
    }
}
