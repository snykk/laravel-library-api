<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookGetRequest extends FormRequest
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
            'filter.title' => 'string|min:2|max:50',
            'filter.description' => 'string|min:2|max:65535',
            'filter.rating' => 'integer|between:0,65535',
            'filter.author_id' => 'integer|between:0,18446744073709551615',
            'filter.publisher_id' => 'integer|between:0,18446744073709551615',
            'filter.created_at' => 'date',
            'filter.updated_at' => 'date',
            'filter.books\.id' => 'integer|between:0,18446744073709551615',
            'filter.books\.title' => 'string|min:2|max:50',
            'filter.books\.description' => 'string|min:2|max:65535',
            'filter.books\.rating' => 'integer|between:0,65535',
            'filter.books\.author_id' => 'integer|between:0,18446744073709551615',
            'filter.books\.publisher_id' => 'integer|between:0,18446744073709551615',
            'filter.books\.created_at' => 'date',
            'filter.books\.updated_at' => 'date',
            'filter.author\.id' => 'integer|between:0,18446744073709551615',
            'filter.author\.name' => 'string|min:2|max:50',
            'filter.author\.created_at' => 'date',
            'filter.author\.updated_at' => 'date',
            'filter.publisher\.id' => 'integer|between:0,18446744073709551615',
            'filter.publisher\.name' => 'string|min:2|max:50',
            'filter.publisher\.created_at' => 'date',
            'filter.publisher\.updated_at' => 'date',
            'page.number' => 'integer|min:1',
            'page.size' => 'integer|between:1,100',
            'search' => 'nullable|string|min:3|max:60',
        ];
    }
}
