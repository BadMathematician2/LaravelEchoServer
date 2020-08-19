<?php


namespace LaravelEchoServer;


use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class LaravelEchoInitAndUp extends Command
{
    protected $signature = 'init-echo {filename?}';

    /**
     * @var string
     */
    protected $description = 'This is a creation of container with laravel-echo-server';

    public function handle()
    {
        $command = new Process(['docker', 'ps', '-f', 'name=laravel_echo_server_l_echo_1']);
        $command->run();
        $containers = $command->getOutput();

        $this->createFile();

        $this->upContainer($containers);
    }

    /**
     * @param string $containers
     */
    private function upContainer($containers)
    {
        if (stristr($containers, "\n") === "\n") {
            if (null === $this->argument('filename')) {
                $this->init();
                \LaravelEcho::createJsonFile();
            } else {
                \LaravelEcho::initWithFile($this->argument('filename'));
            }

            $command = new Process(['docker-compose', '-f', \LaravelEcho::getPath() . '/echo/docker-compose.yml',
                '-p', 'laravel_echo_server', 'up', '-d']);
            $command->run();

        } else {
            echo "Container is already run\n";
        }
    }

    private function createFile()
    {
        $f = fopen(__DIR__ . '/echo/docker-compose.yml', 'w');
        fwrite($f, 'version: "3"' . "\n");
        fwrite($f, 'services:' . "\n");
        fwrite($f, '    l_echo:' . "\n");
        fwrite($f, '        image: badmathematician/echo_redis' . "\n");
        fwrite($f, '        command: bash -c "service redis-server start && cd laravel-echo-server/ && laravel-echo-server client:add && laravel-echo-server start --dir=/laravel-echo-server"' . "\n");
        fwrite($f, '        volumes:' . "\n");
        fwrite($f, '            - ' . __DIR__ . '/echo/:/laravel-echo-server' . "\n");
    }

    private function init()
    {
        if ($this->ask('Do you want to run this server in development mode? (yes/No)', 'No') === 'yes') {
            \LaravelEcho::setParams('devMode', true);
        }
        $answer = $this->ask('Which port would you like to serve from? (6001)', '6001');
        if ($answer !== '') {
            \LaravelEcho::setParams('port', $answer);
        }
        $answer = $this->ask('Enter the host of your Laravel authentication server. (http://localhost)', 'http://localhost');
        if ($answer !== '') {
            \LaravelEcho::setParams('authHost', $answer);
        }
        $answer = $this->ask('Will you be serving on http or https?', 'http');
        if ('https' === $answer) {
            \LaravelEcho::setParams('protocol', 'https');
            $answer = $this->ask('Enter the path to your SSL cert file.');
            \LaravelEcho::setParams('sslKeyPath', $answer);
        }


    }
}
