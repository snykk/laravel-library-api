<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Publisher;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublisherPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Publisher(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Publisher $publisher
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Publisher $publisher): bool
    {
        return $this->can($cmsAdmin, $publisher, 'view');
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
        return $this->can($cmsAdmin, new Publisher(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Publisher $publisher
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Publisher $publisher): bool
    {
        return $this->can($cmsAdmin, $publisher, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Publisher $publisher
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Publisher $publisher): bool
    {
        return $this->can($cmsAdmin, $publisher, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Publisher $publisher
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, Publisher $publisher): bool
    {
        return $this->can($cmsAdmin, $publisher, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Publisher $publisher
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, Publisher $publisher): bool
    {
        return $this->can($cmsAdmin, $publisher, 'forceDelete');
    }
}
