<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalGetRequest extends FormRequest
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
            'filter.user_id' => 'integer|between:0,18446744073709551615',
            'filter.book_id' => 'integer|between:0,18446744073709551615',
            'filter.rental_date' => 'date',
            'filter.rental_duration' => 'integer|between:-2147483647,2147483647',
            'filter.return_date' => 'date',
            'filter.status' => 'string|min:2|max:20',
            'filter.is_due' => 'boolean',
            'filter.created_at' => 'date',
            'filter.updated_at' => 'date',
            'filter.rentals\.id' => 'integer|between:0,18446744073709551615',
            'filter.rentals\.user_id' => 'integer|between:0,18446744073709551615',
            'filter.rentals\.book_id' => 'integer|between:0,18446744073709551615',
            'filter.rentals\.rental_date' => 'date',
            'filter.rentals\.rental_duration' => 'integer|between:-2147483647,2147483647',
            'filter.rentals\.return_date' => 'date',
            'filter.rentals\.status' => 'string|min:2|max:20',
            'filter.rentals\.is_due' => 'boolean',
            'filter.rentals\.created_at' => 'date',
            'filter.rentals\.updated_at' => 'date',
            'filter.user\.id' => 'integer|between:0,18446744073709551615',
            'filter.user\.name' => 'string|min:2|max:255',
            'filter.user\.email' => 'string|email|min:11|max:255',
            'filter.user\.email_verified_at' => 'date',
            'filter.user\.password' => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.user\.reviews' => 'integer|between:-2147483647,2147483647',
            'filter.user\.remember_token' => 'string|min:2|max:100',
            'filter.user\.created_at' => 'date',
            'filter.user\.updated_at' => 'date',
            'filter.book\.id' => 'integer|between:0,18446744073709551615',
            'filter.book\.title' => 'string|min:2|max:50',
            'filter.book\.description' => 'string|min:2|max:65535',
            'filter.book\.rating' => 'numeric',
            'filter.book\.author_id' => 'integer|between:0,18446744073709551615',
            'filter.book\.publisher_id' => 'integer|between:0,18446744073709551615',
            'filter.book\.created_at' => 'date',
            'filter.book\.updated_at' => 'date',
            'page.number' => 'integer|min:1',
            'page.size' => 'integer|between:1,100',
            'search' => 'nullable|string|min:3|max:60',
        ];
    }
}
