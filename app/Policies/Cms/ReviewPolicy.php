<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Review(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Review $review
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Review $review): bool
    {
        return $this->can($cmsAdmin, $review, 'view');
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
        return $this->can($cmsAdmin, new Review(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Review $review
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Review $review): bool
    {
        return $this->can($cmsAdmin, $review, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Review $review
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Review $review): bool
    {
        return $this->can($cmsAdmin, $review, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Review $review
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, Review $review): bool
    {
        return $this->can($cmsAdmin, $review, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Review $review
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, Review $review): bool
    {
        return $this->can($cmsAdmin, $review, 'forceDelete');
    }
}
