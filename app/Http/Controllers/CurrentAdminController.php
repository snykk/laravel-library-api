<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrentAdminRequest;
use App\Http\Resources\CmsAdminResource;
use App\Models\CmsAdmin;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class CurrentAdminController extends Controller
{
    /**
     * Get the currently logged in Cms Admin.
     *
     * @throws AuthenticationException
     *
     * @return mixed
     */
    public function show()
    {
        $admin = auth()->guard(config('api.cms_guard', 'cms-api'))->user();

        if (!($admin instanceof CmsAdmin)) {
            throw new AuthenticationException('You are not authorized to access the endpoint.');
        }

        return $admin->append(['medium_profile_picture', 'small_profile_picture', 'permission_list']);
    }

    /**
     * Update the CMS Admin profile.
     *
     * @param CurrentAdminRequest $request
     *
     * @throws \ErrorException
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     *
     * @return mixed
     */
    public function update(CurrentAdminRequest $request)
    {
        $admin = auth()->guard(config('api.cms_guard', 'cms-api'))->user();

        if (!($admin instanceof CmsAdmin)) {
            throw new AuthenticationException('Invalid CMS Admin object.');
        }

        $admin->fill($request->only(['name', 'email']));

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();
        $admin->saveProfilePicture($request);

        return (new CmsAdminResource($admin))
            ->additional(['info' => 'Your account information has been updated.']);
    }
}
