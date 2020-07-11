<?php

namespace Bavix\Prof\Middleware;

use Bavix\Prof\Services\ProfileLogService;
use Illuminate\Http\Request;
use Closure;

class TerminateMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Note from the beginning of the application...
         */
        if (\config('prof.globalMiddleware', false)) {
            app(ProfileLogService::class)
                ->tick('app:init');
        }

        return $next($request);
    }

    /**
     * @param $request
     * @param $response
     */
    public function terminate($request, $response): void
    {
        /**
         * If there are started ticks, but not completed, we complete it.
         */
        app(ProfileLogService::class)
            ->recordAllTicks();
    }

}
