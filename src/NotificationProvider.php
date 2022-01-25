<?php

namespace HeadlessLaravel\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('notifications', function () {
            return app(Routes::class)->notifications();
        });
    }
}
