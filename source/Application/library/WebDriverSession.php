<?php

namespace Application\Library;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverPoint;
use RapidSpike\Targets\Url;

/**
 * Class WebDriverSession
 * @package Application\Library
 */
class WebDriverSession
{

    /**
     * @param Url $Url
     * @param WebDriverSettings $WebDriverSettings
     * @param ViewPort $ViewPort
     * @param int $timeout_seconds
     * @return RemoteWebDriver
     */
    public static function __init(
        Url $Url, WebDriverSettings
        $WebDriverSettings,
        ViewPort $ViewPort,
        int $timeout_seconds): RemoteWebDriver
    {
        $timeout_ms = ($timeout_seconds * 1000) + 500;

        try {
            $Driver = RemoteWebDriver::create($Url->getUrl(), $WebDriverSettings->__init(), $timeout_ms, $timeout_ms);
            $Driver->manage()->window()->setPosition(new WebDriverPoint($ViewPort->pos_x, $ViewPort->pos_y));
            $Driver->manage()->window()->setSize(new WebDriverDimension($ViewPort->width, $ViewPort->height));
            $Driver->manage()->deleteAllCookies();
            $Driver->manage()->timeouts()->pageLoadTimeout($timeout_seconds);
            $Driver->manage()->timeouts()->implicitlyWait($timeout_seconds);
        } catch (\Exception $e) {
            Utils::out("Remote WebDriver Error! {$e->getMessage()}");
            exit;
        }

        return $Driver;
    }

}
