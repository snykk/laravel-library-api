<?php

namespace App\Http\Requests;

use App\Rules\Forbidden;
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
            'user_id' => new Forbidden(),
            'book_id' => 'required|integer|between:0,18446744073709551615',
            'rental_date' => new Forbidden(),
            'rental_duration' => new Forbidden(),
            'return_date' => new Forbidden(),
            'status' => new Forbidden(),
            'is_due' => new Forbidden(),
        ];
    }
}
