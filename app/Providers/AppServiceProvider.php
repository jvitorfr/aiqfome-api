<?php

namespace App\Providers;

use App\Repositories\AuditLogRepository;
use App\Repositories\ClientRepository;
use App\Repositories\Contracts\IAuditLogRepository;
use App\Repositories\Contracts\IClientRepository;
use App\Repositories\Contracts\IFavoriteRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\UserRepository;
use App\Services\Logging\ThirdPartyLogger;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('thirdparty-logger', function () {
            return new ThirdPartyLogger();
        });
        $this->app->bind(IAuditLogRepository::class, AuditLogRepository::class);
        $this->app->bind(IClientRepository::class, ClientRepository::class);
        $this->app->bind(IFavoriteRepository::class, FavoriteRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
