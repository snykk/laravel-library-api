<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy extends AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the CMS Admin can view any models.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     *
     * @return bool
     */
    public function viewAny(CmsAdmin $cmsAdmin): bool
    {
        return $this->can($cmsAdmin, new Role(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin           $cmsAdmin
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Role $role): bool
    {
        return $this->can($cmsAdmin, $role, 'view');
    }

    /**
     * Determine whether the CMS Admin can create models.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     *
     * @return bool
     */
    public function create(CmsAdmin $cmsAdmin): bool
    {
        return $this->can($cmsAdmin, new Role(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin           $cmsAdmin
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Role $role): bool
    {
        return $this->can($cmsAdmin, $role, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin           $cmsAdmin
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Role $role): bool
    {
        return $this->can($cmsAdmin, $role, 'delete');
    }

    /*
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin           $cmsAdmin
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return bool
     */
//    public function restore(CmsAdmin $cmsAdmin, Role $role): bool
//    {
//        return $this->can($cmsAdmin, $role, 'restore');
//    }

    /*
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin           $cmsAdmin
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return bool
     */
//    public function forceDelete(CmsAdmin $cmsAdmin, Role $role): bool
//    {
//        return $this->can($cmsAdmin, $role, 'forceDelete');
//    }
}
