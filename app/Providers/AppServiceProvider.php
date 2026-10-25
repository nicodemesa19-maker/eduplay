<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Supabase Storage Service
        $this->app->singleton(\App\Services\SupabaseStorageService::class, function ($app) {
            return new \App\Services\SupabaseStorageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register Brevo mailer transport
        Mail::extend('brevo', function ($config) {
            $httpClient = \Symfony\Component\HttpClient\HttpClient::create();
            
            $transport = new \Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport(
                config('services.brevo.key'),
                $httpClient
            );
            
            return new \Symfony\Component\Mailer\Mailer($transport);
        });
    }
}