<?php

namespace App\Providers;

use App\Models\CmsAdmin;
use App\Models\SeoMeta;
use App\Models\Setting;
use App\Policies\Cms\CmsAdminPolicy;
use App\Policies\Cms\RolePolicy;
use App\Policies\Cms\SeoMetaPolicy;
use App\Policies\Cms\SettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;

class CmsAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        CmsAdmin::class => CmsAdminPolicy::class,
        Role::class     => RolePolicy::class,
        SeoMeta::class  => SeoMetaPolicy::class,
        Setting::class  => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
