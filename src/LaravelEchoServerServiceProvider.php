<?php


namespace LaravelEchoServer;


use Illuminate\Support\ServiceProvider;

class LaravelEchoServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('LaravelEchoServer', function () {
            return new LaravelEchoServer();
        });
        $this->commands([
            LaravelEchoInitAndUp::class
        ]);
        $this->configRegister();
    }
    private function configRegister()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-echo-server.php','echo'
        );
    }
}
