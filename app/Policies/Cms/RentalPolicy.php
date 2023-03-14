<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Rental;
use Illuminate\Auth\Access\HandlesAuthorization;

class RentalPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Rental(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Rental $rental
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Rental $rental): bool
    {
        return $this->can($cmsAdmin, $rental, 'view');
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
        return $this->can($cmsAdmin, new Rental(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Rental $rental
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Rental $rental): bool
    {
        return $this->can($cmsAdmin, $rental, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Rental $rental
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Rental $rental): bool
    {
        return $this->can($cmsAdmin, $rental, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Rental $rental
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, Rental $rental): bool
    {
        return $this->can($cmsAdmin, $rental, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Rental $rental
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, Rental $rental): bool
    {
        return $this->can($cmsAdmin, $rental, 'forceDelete');
    }
}
