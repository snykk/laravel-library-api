<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\SeoMeta;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeoMetaPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new SeoMeta(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\SeoMeta  $seoMeta
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, SeoMeta $seoMeta): bool
    {
        return $this->can($cmsAdmin, $seoMeta, 'view');
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
        return $this->can($cmsAdmin, new SeoMeta(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\SeoMeta  $seoMeta
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, SeoMeta $seoMeta): bool
    {
        return $this->can($cmsAdmin, $seoMeta, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\SeoMeta  $seoMeta
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, SeoMeta $seoMeta): bool
    {
        return $this->can($cmsAdmin, $seoMeta, 'delete');
    }

    /*
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\SeoMeta  $seoMeta
     *
     * @return bool
     */
//    public function restore(CmsAdmin $cmsAdmin, SeoMeta $seoMeta): bool
//    {
//        return $this->can($cmsAdmin, $seoMeta, 'restore');
//    }

    /*
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\SeoMeta  $seoMeta
     *
     * @return bool
     */
//    public function forceDelete(CmsAdmin $cmsAdmin, SeoMeta $seoMeta): bool
//    {
//        return $this->can($cmsAdmin, $seoMeta, 'forceDelete');
//    }
}
