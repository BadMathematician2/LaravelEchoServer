<?php


namespace LaravelEchoServer\Commands;


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

        if (\LaravelEcho::isRunning()) {
            $this->info('Container already running');
            return ;
        }

        $c = new Process(['bash', '-c', 'echo $HOME']);
        $c->run();
        $home_path = stristr($c->getOutput(), "\n", true);

        \LaravelEcho::createLink($home_path);

        if (null === $this->argument('filename')) {
            $this->init();
            $this->createJsonFile();
        } else {
            $this->initWithFile($this->argument('filename'));
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
                \LaravelEcho::setParam($key, $value);
            }
        }
        $this->createJsonFile();

    }

    private function init()
    {
        $asks_list = config('asks');

        foreach ($asks_list as $item) {
            $this->takeAnswers($item);
        }
    }

    private function takeAnswers($item)
    {
        $answer = $this->ask($item['ask'], $item['value']);
        $validator = $item['validator'] ?? function($value) {
          return   $value !== '';
        };

        if ($validator($answer)) {
            $item['value'] = $answer;

            if (isset($item['others'])) {
                foreach ($item['others'] as $other) {
                    $this->takeAnswers($other);
                }
            }
        }

        \LaravelEcho::setParam($item['key'], $item['value']);
    }

    private function createJsonFile()
    {
        return file_put_contents(\LaravelEcho::getEchoPath('laravel-echo-server.json') , json_encode(\LaravelEcho::getParams()));

    }
}
