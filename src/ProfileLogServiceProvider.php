<?php

namespace Bavix\Prof;

use Bavix\Prof\Commands\BulkWrite;
use Bavix\Prof\Middleware\ProfileLogMiddleware;
use Bavix\Prof\Middleware\TerminateMiddleware;
use Bavix\Prof\Services\BulkService;
use Bavix\Prof\Services\ProfileLogService;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class ProfileLogServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([BulkWrite::class]);
            return;
        }

        $this->mergeConfigFrom(\dirname(__DIR__) . '/config/config.php', 'prof');
        if (function_exists('config_path')) {
            $this->publishes([
                dirname(__DIR__) . '/config/config.php' => config_path('prof.php'),
            ], 'laravel-prof-config');
        }

        $this->app->singleton(ProfileLogMiddleware::class);
        $this->app->singleton(ProfileLogService::class);
        $this->app->singleton(BulkService::class);

        /**
         * @var \Illuminate\Foundation\Http\Kernel $kernel
         */
        if (!$this->app->shouldSkipMiddleware() && $this->handlingApprovedRequest()) {
            $kernel = $this->app[Kernel::class];
            $kernel->appendMiddlewareToGroup('web', ProfileLogMiddleware::class);
            $kernel->appendMiddlewareToGroup('api', ProfileLogMiddleware::class);
            $kernel->pushMiddleware(TerminateMiddleware::class);
        }
    }

    /**
     * @see https://github.com/laravel/telescope/blob/3.x/src/Telescope.php#L187-L198
     */
    protected function handlingApprovedRequest(): bool
    {
        return !$this->app->runningInConsole() && !$this->app['request']->is(
                \array_merge([
                    \config('telescope.path', 'telescope') . '*',
                    'telescope-api*',
                    'vendor/telescope*',
                    'horizon*',
                    'vendor/horizon*',
                ], \config('telescope.ignore_paths', []))
            );
    }

}
