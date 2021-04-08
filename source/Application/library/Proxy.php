<?php

declare(strict_types=1);

namespace Application\Library;

use RapidSpike\BrowserMobProxy\Client;
use RapidSpike\Targets\Url;

/**
 * Class Proxy
 * @package Application\Library
 */
class Proxy
{

    const TIMEOUT = 30;
    const HOST = 'localhost:8080';

    /**
     * @var Client
     */
    private $Client;

    /**
     * Proxy constructor.
     * @param Url $Url
     */
    public function __construct(Url $Url)
    {
        try {
            $Client = new Client("{$Url->getHost()}:{$Url->getPort()}");
            $Client->open('trustAllServers=true&useEcc=true');
            $Client->timeouts(['connection' => self::TIMEOUT, 'request' => self::TIMEOUT]);

            Utils::out("Proxy started on {$Client->url}");

            $this->Client = $Client;
        } catch (\Exception $e) {
            Utils::out("Proxy Error! {$e->getMessage()}");
            exit(1);
        }
    }

    /**
     * Get the proxy Client object
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->Client;
    }

    /**
     * Create a new HAR in the proxy
     * @param string $title
     */
    public function newHar(string $title): void
    {
        $this->getClient()->newHar($title);
    }

    /**
     * Store an existing HAR
     * @param string $file_location
     * @return false|string
     */
    public function storeHar(string $file_location)
    {
        $arrHarData = $this->getClient()->har;
        return (file_put_contents($file_location, json_encode($arrHarData, JSON_PRETTY_PRINT)) !== false) ? $file_location : false;
    }

}
