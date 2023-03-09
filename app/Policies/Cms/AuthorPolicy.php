<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Author;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Author(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Author $author
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Author $author): bool
    {
        return $this->can($cmsAdmin, $author, 'view');
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
        return $this->can($cmsAdmin, new Author(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Author $author
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Author $author): bool
    {
        return $this->can($cmsAdmin, $author, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Author $author
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Author $author): bool
    {
        return $this->can($cmsAdmin, $author, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Author $author
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, Author $author): bool
    {
        return $this->can($cmsAdmin, $author, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Author $author
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, Author $author): bool
    {
        return $this->can($cmsAdmin, $author, 'forceDelete');
    }
}
