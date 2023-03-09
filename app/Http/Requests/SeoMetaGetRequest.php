<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoMetaGetRequest extends FormRequest
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
            'filter.seo_url'                    => 'string|min:2|max:255',
            'filter.model'                      => 'string|min:2|max:255',
            'filter.foreign_key'                => 'integer|between:0,18446744073709551615',
            'filter.locale'                     => 'string|min:2|max:8',
            'filter.seo_title'                  => 'string|min:2|max:60',
            'filter.seo_description'            => 'string|min:2|max:160',
            'filter.open_graph_type'            => 'string|min:2|max:32',
            'filter.deleted_at'                 => 'date',
            'filter.created_at'                 => 'date',
            'filter.updated_at'                 => 'date',
            'filter.seo_metas\.id'              => 'integer|between:0,18446744073709551615',
            'filter.seo_metas\.seo_url'         => 'string|min:2|max:255',
            'filter.seo_metas\.model'           => 'string|min:2|max:255',
            'filter.seo_metas\.foreign_key'     => 'integer|between:0,18446744073709551615',
            'filter.seo_metas\.locale'          => 'string|min:2|max:8',
            'filter.seo_metas\.seo_title'       => 'string|min:2|max:60',
            'filter.seo_metas\.seo_description' => 'string|min:2|max:160',
            'filter.seo_metas\.open_graph_type' => 'string|min:2|max:32',
            'filter.seo_metas\.deleted_at'      => 'date',
            'filter.seo_metas\.created_at'      => 'date',
            'filter.seo_metas\.updated_at'      => 'date',
            'page.number'                       => 'integer|min:1',
            'page.size'                         => 'integer|between:1,100',
            'search'                            => 'nullable|string|min:3|max:60',
        ];
    }
}
