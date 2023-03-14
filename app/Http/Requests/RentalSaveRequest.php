<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalSaveRequest extends FormRequest
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
            'user_id' => 'required|integer|between:0,18446744073709551615',
            'book_id' => 'required|integer|between:0,18446744073709551615',
            'rental_date' => 'required|date',
            'rental_duration' => 'required|integer|between:-2147483647,2147483647',
            'return_date' => 'required|date',
            'status' => 'required|string|min:2|max:20',
            'is_due' => 'required|boolean',
        ];
    }
}
