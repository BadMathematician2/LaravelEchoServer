<?php


namespace App\Packages\LaravelEchoServer;


use Illuminate\Support\ServiceProvider;

class LaravelEchoServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('LaravelEchoServerContainer', function () {
            return $this->app->make(LaravelEchoServerContainer::class);
        });
    }
}
