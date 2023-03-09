<?php

namespace App\Policies\Cms;

use App\Models\CmsAdmin;
use App\Models\Book;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy extends AbstractPolicy
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
        return $this->can($cmsAdmin, new Book(), 'viewAny');
    }

    /**
     * Determine whether the CMS Admin can view the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Book $book
     *
     * @return bool
     */
    public function view(CmsAdmin $cmsAdmin, Book $book): bool
    {
        return $this->can($cmsAdmin, $book, 'view');
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
        return $this->can($cmsAdmin, new Book(), 'create');
    }

    /**
     * Determine whether the CMS Admin can update the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Book $book
     *
     * @return bool
     */
    public function update(CmsAdmin $cmsAdmin, Book $book): bool
    {
        return $this->can($cmsAdmin, $book, 'update');
    }

    /**
     * Determine whether the CMS Admin can delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Book $book
     *
     * @return bool
     */
    public function delete(CmsAdmin $cmsAdmin, Book $book): bool
    {
        return $this->can($cmsAdmin, $book, 'delete');
    }

    /**
     * Determine whether the CMS Admin can restore the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Book $book
     *
     * @return bool
     */
    public function restore(CmsAdmin $cmsAdmin, Book $book): bool
    {
        return $this->can($cmsAdmin, $book, 'restore');
    }

    /**
     * Determine whether the CMS Admin can permanently delete the model.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     * @param \App\Models\Book $book
     *
     * @return bool
     */
    public function forceDelete(CmsAdmin $cmsAdmin, Book $book): bool
    {
        return $this->can($cmsAdmin, $book, 'forceDelete');
    }
}
