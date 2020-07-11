<?php

namespace Bavix\Prof\Middleware;

use Bavix\Prof\Services\ProfileLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Closure;

class ProfileLogMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Run in front of the controller to evaluate its operation.
         */
        app(ProfileLogService::class)
            ->tick($this->routeAction($request));

        return $next($request);
    }

    /**
     * We get routeAction for the controller and for the closure.
     *
     * @param Request $request
     * @return string
     */
    protected function routeAction(Request $request): string
    {
        $routeName = Route::currentRouteName() ?? Route::currentRouteAction();
        if (!$routeName) {
            $routeName = 'app:' . \str_replace('/', ':', $route->uri);
        }

        return $routeName;
    }

}
