<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractPolicy
{
    /**
     * Determine if the given CMS Admin has permission to do the given ability
     * toward the given model object.
     *
     * @param CmsAdmin $cmsAdmin
     * @param Model    $model
     * @param string   $ability
     *
     * @return bool
     */
    protected function can(CmsAdmin $cmsAdmin, Model $model, string $ability): bool
    {
        $ability = $this->getPermissionKey($ability, $model);

        return $this->hasPermissions($cmsAdmin) && $cmsAdmin->can($ability);
    }

    /**
     * Generate the permission key.
     *
     * @param string $ability
     * @param Model  $model
     *
     * @return string
     */
    protected function getPermissionKey(string $ability, Model $model): string
    {
        return 'cms.'.$model->getTable().'.'.$ability;
    }

    /**
     * Determine if the current admin object has permissions.
     *
     * @param CmsAdmin $cmsAdmin
     *
     * @return bool
     */
    protected function hasPermissions(CmsAdmin $cmsAdmin): bool
    {
        return method_exists($cmsAdmin, 'can');
    }
}
