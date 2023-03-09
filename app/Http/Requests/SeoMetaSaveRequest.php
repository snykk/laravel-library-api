<?php

namespace App\Http\Requests;

use App\Contracts\FileUploadRequest;
use Illuminate\Foundation\Http\FormRequest;

class SeoMetaSaveRequest extends FormRequest implements FileUploadRequest
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
            'seo_url'         => 'nullable|required_without_all:model,foreign_key|string|min:2|max:255',
            'model'           => 'nullable|required_without:seo_url|string|min:2|max:255',
            'foreign_key'     => 'nullable|required_without:seo_url|integer|between:0,18446744073709551615',
            'locale'          => 'nullable|required_without:seo_url|string|min:2|max:8',
            'seo_title'       => 'required|string|min:2|max:60',
            'seo_description' => 'required|string|min:2|max:160',
            'open_graph_type' => 'required|string|min:2|max:32',
            'seo_image'       => 'nullable|image|mimes:jpeg,png',
        ];
    }
}
