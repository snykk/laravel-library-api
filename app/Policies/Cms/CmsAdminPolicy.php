<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

class CmsAdminPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new CmsAdmin(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\CmsAdmin $model
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, CmsAdmin $model): bool
    {
        return $this->can($cmsAdmin, $model, 'view');
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
        return $this->can($cmsAdmin, new CmsAdmin(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\CmsAdmin $model
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, CmsAdmin $model): bool
    {
        return $this->can($cmsAdmin, $model, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\CmsAdmin $model
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, CmsAdmin $model): bool
    {
        return $this->can($cmsAdmin, $model, 'delete');
    }

    /*
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\CmsAdmin $model
     *
     * @return bool
     */
//    public function restore(CmsAdmin $cmsAdmin, CmsAdmin $model): bool
//    {
//        return $this->can($cmsAdmin, $model, 'restore');
//    }

    /*
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\CmsAdmin $model
     *
     * @return bool
     */
//    public function forceDelete(CmsAdmin $cmsAdmin, CmsAdmin $model): bool
//    {
//        return $this->can($cmsAdmin, $model, 'forceDelete');
//    }
}
