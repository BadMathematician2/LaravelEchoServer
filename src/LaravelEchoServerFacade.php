<?php


namespace LaravelEchoServer;


use Illuminate\Support\Facades\Facade;

class LaravelEchoServerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelEchoServerContainer';
    }

}
