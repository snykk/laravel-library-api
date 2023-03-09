<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSaveRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:50',
            'description' => 'required|string|min:2|max:65535',
            'rating' => 'required|integer|between:0,65535',
            'author_id' => 'required|integer|between:0,18446744073709551615',
            'publisher_id' => 'required|integer|between:0,18446744073709551615',
        ];
    }
}
