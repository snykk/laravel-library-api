<?php

namespace App\Auth;

use App\Models\CmsAdmin;
use Illuminate\Auth\EloquentUserProvider;

class CmsAdminProvider extends EloquentUserProvider
{
    /**
     * Retrieve an admin by the given credentials.
     *
     * @param array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $admin = parent::retrieveByCredentials($credentials);

        return (($admin instanceof CmsAdmin) && $admin->hasPermissionTo('access-cms', config('api.cms_guard'))) ?
            $admin : null;
    }
}
