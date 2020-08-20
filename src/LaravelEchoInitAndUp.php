<?php


namespace LaravelEchoServer;


use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class LaravelEchoInitAndUp extends Command
{
    /**
     * @var string
     */
    protected $signature = 'init-echo {filename?}';

    /**
     * @var string
     */
    protected $description = 'This is a creation of container with laravel-echo-server';

    public function handle()
    {
        $c = new Process(['bash', '-c', 'echo $HOME']);
        $c->run();
        $home_path = stristr($c->getOutput(), "\n", true);

        \LaravelEcho::createLink($home_path);

        if (\LaravelEcho::isRunning()) {
            if (null === $this->argument('filename')) {
                $this->init();
                $this->createJsonFile();
            } else {
                $this->initWithFile($this->argument('filename'));
            }
        }

        \LaravelEcho::upContainer();
    }

    /**
     * @param string $filename
     */
    public function initWithFile($filename)
    {
        $f = fopen($filename, 'r');
        $params = [];

        $s = fgets($f, 99);
        while (null != $s) {
            try {
                $st = explode('=', $s);
                $params[$st[0]] = stristr($st[1], "\n", true);
            } catch (\Exception $exception) {}
            $s = fgets($f, 99);
        }
        fclose($f);

        foreach ($params as $key => $value) {
            if (key_exists($key, \LaravelEcho::getParams())) {
                \LaravelEcho::setParams($key, $value);
            }
        }
        $this->createJsonFile();

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

    private function createJsonFile()
    {
        file_put_contents(__DIR__ . '/echo/laravel-echo-server.json', json_encode(\LaravelEcho::getParams()));

    }
}
