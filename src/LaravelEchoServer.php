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
    public function setParams($key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * @param string $home_path
     */
    public function createLink($home_path)
    {
        $c = new Process(['ln', '-f', '-s', __DIR__ . '/echo/', $home_path]);
        $c->run();
        $c->wait();
    }

    public function isRunning()
    {
        $command = new Process(['docker', 'ps', '-f', 'name=laravel_echo_server_l_echo_1']);
        $command->run();

        return stristr($command->getOutput(), "\n") === "\n";
    }

    public function upContainer()
    {
        $command = new Process(['docker-compose', '-f', __DIR__ . '/docker-compose.yml',
            '-p', 'laravel_echo_server', 'up', '-d']);
        $command->run();
    }


}
