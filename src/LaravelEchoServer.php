<?php


namespace LaravelEchoServer;


use Symfony\Component\Process\Process;

class LaravelEchoServer
{
    /**
     * @var array
     */
    private $params;

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function __construct()
    {
        $this->params = config('echo');

    }

    /**
     * @param $key
     * @param $value
     */
    public function setParam($key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * @param string $home_path
     * @return Process
     */
    public function createLink($home_path)
    {
        $c = new Process(['ln', '-f', '-s',$this->getPathTo('/echo/'), $home_path]);
        $c->run();

        return $c;
    }

    public function isRunning()
    {
        $command = new Process(['docker', 'ps', '-f', 'name=laravel_echo_server_l_echo_1']);
        $command->run();

        return empty(trim(stristr($command->getOutput(), "\n")));
    }

    public function upContainer()
    {
        $command = new Process(['docker-compose', '-f', $this->getPathTo('/docker-compose.yml'),
            '-p', 'laravel_echo_server', 'up', '-d']);
        $command->run();

        return $command;
    }

    public function getPathTo($to)
    {
        return __DIR__ . $to;
    }

}
