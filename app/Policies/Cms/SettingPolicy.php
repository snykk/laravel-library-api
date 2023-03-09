<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Setting(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Setting  $setting
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Setting $setting): bool
    {
        return $this->can($cmsAdmin, $setting, 'view');
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
        return $this->can($cmsAdmin, new Setting(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Setting  $setting
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Setting $setting): bool
    {
        return $this->can($cmsAdmin, $setting, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Setting  $setting
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Setting $setting): bool
    {
        return $this->can($cmsAdmin, $setting, 'delete');
    }

    /*
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Setting  $setting
     *
     * @return bool
     */
//    public function restore(CmsAdmin $cmsAdmin, Setting $setting): bool
//    {
//        return $this->can($cmsAdmin, $setting, 'restore');
//    }

    /*
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Setting  $setting
     *
     * @return bool
     */
//    public function forceDelete(CmsAdmin $cmsAdmin, Setting $setting): bool
//    {
//        return $this->can($cmsAdmin, $setting, 'forceDelete');
//    }
}
