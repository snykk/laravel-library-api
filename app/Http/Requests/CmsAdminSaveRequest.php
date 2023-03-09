<?php

namespace App\Http\Requests;

use App\Contracts\FileUploadRequest;
use App\Models\CmsAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsAdminSaveRequest extends FormRequest implements FileUploadRequest
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
     * Get the CMS Admin model which is related to the current request.
     *
     * @return CmsAdmin|null
     */
    protected function getCmsAdmin(): ?CmsAdmin
    {
        $object = $this->route('cms_admin');

        return ($object instanceof CmsAdmin) ? $object : null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $admin = $this->getCmsAdmin();

        if ($admin instanceof CmsAdmin) {
            return [
                'name'             => 'required|min:2',
                'email'            => [
                    'required',
                    'string',
                    'email',
                    'min:11',
                    'max:255',
                    Rule::unique('cms_admins')->ignore($admin->getKey()),
                ],
                'password' => [
                    'required_with:password_confirmation',
                    'nullable',
                    'min:8',
                    'confirmed',
                ],
                'password_confirmation' => [
                    'required_with:password',
                    'nullable',
                ],
                'profile_picture'   => 'nullable|image|mimes:jpeg,png',
                'role_names'        => 'required',
            ];
        }

        return [
            'name'             => 'required|min:2',
            'email'            => [
                'required',
                'string',
                'email',
                'min:11',
                'max:255',
                Rule::unique('cms_admins'),
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'min:8',
            ],
            'profile_picture'   => 'nullable|image|mimes:jpeg,png',
            'role_names'        => 'required',
        ];
    }
}
