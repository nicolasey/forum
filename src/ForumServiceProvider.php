<?php
namespace Nicolasey\Forum;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ForumServiceProvider extends ServiceProvider
{
    protected $namespace = "Nicolasey\Forum\Http\Controller";

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'forum');

        $this->mapApiRoutes();
    }

    public function register()
    {
        //
    }

    private function mapApiRoutes()
    {
        Route::namespace($this->namespace)
            ->middleware("api")
            ->prefix('api')
            ->group(function () {
                require __DIR__.'/routes/api.php';
            });
    }
}