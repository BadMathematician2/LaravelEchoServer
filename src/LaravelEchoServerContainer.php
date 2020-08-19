<?php


namespace App\Packages\LaravelEchoServer;


class LaravelEchoServerContainer
{
    /**
     * @var array
     */
    public $params = [
        'authHost' => 'http://localhost',
        'authEndpoint' => '/broadcasting/auth',
        'clients' => [],
        'database' => 'redis',
        'databaseConfig' => [
            'redis' => [],
            'sqlite' => ['databasePath' => '/database/laravel-echo-server.sqlite']
        ],
        'devMode' => null,
        'host' => null,
        'port' => '6001',
        'protocol' => 'http',
        'sockerio' => [],
        'secureOptions' => 67108864,
        'sslCertPath' => '',
        'sslKeyPath' => '',
        'sslCertChainPath' => '',
        'sslPassphrase' => '',
        'subscribers' => [
            'http' => true,
            'redis' => true
        ],
        'apiOriginAllow' => [
            'allowCors' => false,
            'allowOrigin' => '',
            'allowMethods' => '',
            'allowHeaders' => ''
        ]
    ];

    /**
     * @param $key
     * @param $value
     */
    public function setParams($key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function createJsonFile()
    {
        $f = fopen(__DIR__ . '/echo/laravel-echo-server.json', 'w+');
        fwrite($f, json_encode($this->params));
        fclose($f);

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
            if (key_exists($key, $this->params)) {
                $this->params[$key] = $value;
            }
        }
        $this->createJsonFile();

    }

    /**
     * @return string
     */
    public function getPath()
    {
        return __DIR__;
    }



}
