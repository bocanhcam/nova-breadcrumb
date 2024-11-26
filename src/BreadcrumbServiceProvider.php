<?php

namespace Bocanhcam\NovaBreadcrumb;

use Bocanhcam\NovaBreadcrumb\Console\Commands\ReplaceBreadcrumbsComponent;
use Bocanhcam\NovaBreadcrumb\Http\Middleware\BreadcrumbReplacement;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->console();
        $this->middleware();

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-breadcrumb', __DIR__.'/../dist/js/asset.js');
            Nova::style('nova-breadcrumb', __DIR__.'/../dist/css/asset.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * @return void
     */
    public function console(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(ReplaceBreadcrumbsComponent::class);
        }
    }

    /**
     * @return void
     */
    public function middleware(): void
    {
        if ($this->app['router']->hasMiddlewareGroup('nova')) {
            $this->app['router']->pushMiddlewareToGroup('nova', BreadcrumbReplacement::class);
            return;
        }

        if (!$this->app->configurationIsCached()) {
            config()->set('nova.middleware', array_merge(
                config('nova.middleware', []),
                [BreadcrumbReplacement::class]
            ));
        }
    }
}
