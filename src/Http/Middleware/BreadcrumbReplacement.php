<?php

namespace Bocanhcam\NovaBreadcrumb\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Middleware;
use Inertia\Response;
use Laravel\Nova\Http\Controllers\Pages\AttachableController;
use Laravel\Nova\Http\Controllers\Pages\AttachedResourceUpdateController;
use Laravel\Nova\Http\Controllers\Pages\DashboardController;
use Laravel\Nova\Http\Controllers\Pages\LensController;
use Laravel\Nova\Http\Controllers\Pages\ResourceCreateController;
use Laravel\Nova\Http\Controllers\Pages\ResourceDetailController;
use Laravel\Nova\Http\Controllers\Pages\ResourceIndexController;
use Laravel\Nova\Http\Controllers\Pages\ResourceReplicateController;
use Laravel\Nova\Http\Controllers\Pages\ResourceUpdateController;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use ReflectionClass;

class BreadcrumbReplacement extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $routeController = $request->route()->getController();

        if ( $this->isPageController($routeController) && Nova::breadcrumbsEnabled()) {
            if ($response->original instanceof View) {
                $responseData = $response->original->getData();
                $responsePage = $responseData['page'];
            }
            else {
                $responsePage = $response->original;
            }

            if (!is_array($responsePage)) {
                return $response;
            }

            $pageType = $this->pageType($routeController);

            $request = NovaRequest::createFrom($request);

            if ($pageType === 'dashboard') {
                $resource = Nova::dashboardForKey($request->route("name"), $request);
            }else{
                $resourceClass = Nova::resourceForKey($request->resource);

                $resource = new $resourceClass();
            }

            $method = 'breadcrumbsFor'.ucfirst($pageType);

            $breadcrumbs = [];
            if ($resource !== null && method_exists($resource, $method)) {
                $breadcrumbs = $resource->$method($request);
            }

            $responsePage['props'] ??= [];
            $responsePage['props']['breadcrumbs'] = $breadcrumbs;

            return Inertia::render($responsePage['component'], $responsePage['props']);
        }else{
            return $response;
        }
    }

    /**
     * @param $controller
     * @return bool
     */
    protected function isPageController($controller): bool
    {
        if (empty($controller)){
            return false;
        }

        return ((new ReflectionClass($controller))?->getNamespaceName() ?? false) === "Laravel\Nova\Http\Controllers\Pages";
    }

    /**
     * @param $routeController
     * @return string|null
     */
    protected function pageType($routeController): ?string
    {
        if (is_object($routeController)) {
            $className = get_class($routeController);

            return match ($className) {
                ResourceDetailController::class => "detail",
                ResourceIndexController::class => "index",
                ResourceCreateController::class => "create",
                ResourceUpdateController::class => "update",
                ResourceReplicateController::class => "replicate",
                AttachableController::class, AttachedResourceUpdateController::class => "attach",
                DashboardController::class => "dashboard",
                LensController::class => "lens",
                default => null,
            };
        }

        return null;
    }
}
