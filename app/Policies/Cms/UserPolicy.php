<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new User(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, User $user): bool
    {
        return $this->can($cmsAdmin, $user, 'view');
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
        return $this->can($cmsAdmin, new User(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, User $user): bool
    {
        return $this->can($cmsAdmin, $user, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, User $user): bool
    {
        return $this->can($cmsAdmin, $user, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, User $user): bool
    {
        return $this->can($cmsAdmin, $user, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, User $user): bool
    {
        return $this->can($cmsAdmin, $user, 'forceDelete');
    }
}
