<?php

namespace Application\Library;

use RapidSpike\Targets\Url;
use Facebook\WebDriver\{Remote\RemoteWebDriver, WebDriverDimension, WebDriverPoint};

/**
 * Class WebDriverSession
 * @package Application\Library
 */
class WebDriverSession
{

    /**
     * Initialise an instance of the WebDriver RemoteWebDriver class, setting the required things to it
     * @param Url $Url
     * @param Model\WebDriverSettings $WebDriverSettings $WebDriverSettings
     * @param Model\ViewPort $ViewPort $ViewPort
     * @param int $timeout_seconds
     * @return RemoteWebDriver
     */
    public static function init(
        Url $Url,
        Model\WebDriverSettings $WebDriverSettings,
        Model\ViewPort $ViewPort,
        int $timeout_seconds
    ): RemoteWebDriver
    {
        $timeout_ms = ($timeout_seconds * 1000) + 500;

        try {
            // Create a session in the Selenium server
            $Driver = RemoteWebDriver::create(
                $Url->getUrl(),
                $WebDriverSettings->generateDesiredCapabilities(),
                $timeout_ms,
                $timeout_ms
            );

            // Configure the Chrome window as per the required settings
            $Driver->manage()->window()->setPosition(
                new WebDriverPoint($ViewPort->pos_x, $ViewPort->pos_y)
            );

            $Driver->manage()->window()->setSize(
                new WebDriverDimension($ViewPort->width, $ViewPort->height)
            );

            // Empty the cookie jar - not entirely necessary but there's no harm in doing it
            $Driver->manage()->deleteAllCookies();

            // Apply timeout settings
            $Driver->manage()->timeouts()->pageLoadTimeout($timeout_seconds);
            $Driver->manage()->timeouts()->implicitlyWait($timeout_seconds);
        } catch (\Exception $e) {
            Utils::out("Remote WebDriver Error! {$e->getMessage()}");
            exit(1);
        }

        return $Driver;
    }

}
