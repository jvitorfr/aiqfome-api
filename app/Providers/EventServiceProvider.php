<?php

namespace App\Providers;

use App\Events\AuditLogEvent;
use App\Listeners\AuditLogListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        AuditLogEvent::class => [
            AuditLogListener::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
