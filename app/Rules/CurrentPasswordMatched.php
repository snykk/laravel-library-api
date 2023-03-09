<?php

namespace App\Rules;

use App\Models\CmsAdmin;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CurrentPasswordMatched implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $admin = auth()->guard(config('api.cms_guard', 'cms-api'))->user();

        if (!($admin instanceof CmsAdmin)) {
            return false;
        }

        return Hash::check($value, data_get($admin, 'password'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The given :attribute does not match with your current password.';
    }
}
