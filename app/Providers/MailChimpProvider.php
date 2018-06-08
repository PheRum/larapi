<?php

namespace App\Providers;

use App\Services\MailChimp;
use Illuminate\Support\ServiceProvider;

class MailChimpProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('mail-chimp', function () {
            return $this->app->make(MailChimp::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mail-chimp'];
    }
}
