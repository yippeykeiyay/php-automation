<?php

namespace Application;

use Application\Library\ChromeOptions;
use Application\Library\Proxy;
use Application\Library\Resolution;
use Application\Library\Utils;
use Application\Library\ViewPort;
use Application\Library\WebDriverSession;
use Application\Library\WebDriverSettings;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use RapidSpike\Targets\Url;

date_default_timezone_set('UTC');

/**
 * Class TestStrap for bootstrapping everything required to run a test
 *
 * @package Application
 */
class TestStrap
{

    /**
     * @var float|null
     */
    private static $_start = null;

    /**
     * @var Proxy
     */
    protected $Proxy;

    /**
     * @var RemoteWebDriver
     */
    protected $RemoteWebDriver;

    public $timeout_seconds = 45;
    public $res_width = 1920;
    public $res_height = 1080;
    public $vp_width = 1440;
    public $vp_height = 900;
    public $proxy_url = 'http://localhost:8080';
    public $selenium_url = 'http://localhost:4444/wd/hub';

    /**
     * TestStrap constructor.
     * Start the timer.
     */
    public function __construct()
    {
        self::$_start = microtime(true);
    }

    /**
     * End the timer.
     */
    public function __destruct()
    {
        $run_time_s = round((microtime(true) - self::$_start), 3, PHP_ROUND_HALF_UP);
        Utils::out("Completed in {$run_time_s} seconds");
        $this->getRemoteWebDriver()->quit();
    }

    /**
     * @return Proxy
     */
    public function getProxy(): Proxy
    {
        return $this->Proxy;
    }

    /**
     * @return RemoteWebDriver
     */
    public function getRemoteWebDriver(): RemoteWebDriver
    {
        return $this->RemoteWebDriver;
    }

    /**
     * Build the test environment using some Chrome options if provided
     *
     * @param array $arrChromeOptions
     *
     * @return $this
     */
    public function buildEnvironment(array $arrChromeOptions = []): TestStrap
    {
        // Setup a Proxy Client
        $this->Proxy = new Proxy(new Url($this->proxy_url));

        // Window resolution settings
        $Resolution = new Resolution($this->res_width, $this->res_height);

        // Start the remote session
        $this->RemoteWebDriver = WebDriverSession::generate(
            new Url($this->selenium_url),
            $this->_buildWebDriverSettings((new ChromeOptions)->addOptions($arrChromeOptions), $Resolution),
            new ViewPort($this->vp_width, $this->vp_height, $Resolution),
            $this->timeout_seconds
        );

        return $this;
    }

    /**
     * Build the Webdriver session's settings
     *
     * @param ChromeOptions $ChromeOptions
     *
     * @param Resolution $Resolution
     *
     * @return WebDriverSession
     */
    private function _buildWebDriverSettings(ChromeOptions $ChromeOptions, Resolution $Resolution): WebDriverSettings
    {
        // Setup WebDriver
        $WebDriverSettings = new WebDriverSettings($ChromeOptions, $Resolution);

        // Apply a proxy
        $WebDriverSettings->setCapability(WebDriverCapabilityType::PROXY, [
            'proxyType' => 'manual',
            'httpProxy' => $this->Proxy->getClient()->url,
            'sslProxy' => $this->Proxy->getClient()->url
        ]);

        return $WebDriverSettings;
    }

}
