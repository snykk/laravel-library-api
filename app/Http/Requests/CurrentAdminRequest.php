<?php

namespace App\Http\Requests;

use App\Contracts\FileUploadRequest;
use App\Models\CmsAdmin;
use App\Rules\CurrentPasswordMatched;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrentAdminRequest extends FormRequest implements FileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
     * @throws AuthenticationException
     *
     * @return array
     */
    public function rules(): array
    {
        $cmsAdmin = auth()->guard(config('api.cms_guard', 'cms-api'))->user();
        if (!($cmsAdmin instanceof CmsAdmin)) {
            throw new AuthenticationException();
        }

        return [
            'name'             => 'required|min:2',
            'email'            => [
                'required',
                'string',
                'email',
                'min:11',
                'max:255',
                Rule::unique('cms_admins')->ignore($cmsAdmin->getKey()),
            ],
            'current_password' => [
                'required_with:password',
                'required_with:password_confirmation',
                'nullable',
                new CurrentPasswordMatched(),
            ],
            'password' => [
                'required_with:current_password',
                'required_with:password_confirmation',
                'nullable',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => [
                'required_with:current_password',
                'required_with:password',
                'nullable',
            ],
            'profile_picture'   => 'nullable|image|mimes:jpeg,png',
        ];
    }
}
