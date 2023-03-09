<?php

namespace App\Providers;

use App\Auth\CmsAdminProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (config('telescope.enabled')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        if (Str::startsWith(request()->path(), config('api.cms_path_prefix'))) {
            $this->app->register(CmsAuthServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCmsAdminProvider();
        $this->registerWhereLikeMacroToBuilder();
    }

    /**
     * Register CMS Admin provider.
     *
     * @return void
     */
    protected function registerCmsAdminProvider(): void
    {
        \Auth::provider('cms_admins', static function (Application $app, array $config) {
            return new CmsAdminProvider($app->make(Hasher::class), $config['model']);
        });
    }

    /**
     * Register whereLike macro to the Eloquent Builder.
     *
     * @return void
     */
    protected function registerWhereLikeMacroToBuilder(): void
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });

                            return $query;
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");

                            return $query;
                        }
                    );
                }
            });

            return $this;
        });
    }
}
