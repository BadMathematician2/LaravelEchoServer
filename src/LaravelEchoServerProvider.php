<?php


namespace LaravelEchoServer;


use Illuminate\Support\ServiceProvider;
use LaravelEchoServer\Commands\LaravelEchoInitAndUp;

class LaravelEchoServerProvider extends ServiceProvider
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
        $this->mergeConfigFrom(
            __DIR__ . '/config/asks_list.php','asks'
        );
    }

}
