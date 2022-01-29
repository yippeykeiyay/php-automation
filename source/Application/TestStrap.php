<?php

declare(strict_types=1);

namespace Application;

use RapidSpike\Targets\Url;
use Application\Library\{WebDriverSession, Utils};
use Application\Library\Model\{ChromeOptions, Proxy, Resolution, ViewPort, WebDriverSettings};
use Facebook\WebDriver\Remote\{RemoteWebDriver, WebDriverCapabilityType};

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
    private static ?float $_start = null;

    /**
     * @var ?TestConfig
     */
    private ?TestConfig $TestConfig = null;

    /**
     * @var ?Proxy
     */
    protected ?Proxy $Proxy = null;

    /**
     * @var RemoteWebDriver
     */
    protected RemoteWebDriver $RemoteWebDriver;

    /**
     * TestStrap constructor.
     * Start the timer.
     */
    public function __construct(TestConfig $TestConfig)
    {
        self::$_start = microtime(true);
        $this->TestConfig = $TestConfig;
    }

    /**
     * End the timer.
     */
    public function __destruct()
    {
        $run_time_s = round((microtime(true) - self::$_start), 3, PHP_ROUND_HALF_UP);
        Utils::out("Completed in $run_time_s seconds");

        if ($this->RemoteWebDriver instanceof RemoteWebDriver) {
            // Quit the Selenium and browser session
            $this->RemoteWebDriver->quit();
        }

        if ($this->Proxy instanceof Proxy) {
            // Close the proxy client
            $this->Proxy->getClient()->close();
        }
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
     * @return $this
     */
    public function buildEnvironment(): TestStrap
    {
        if (true === $this->TestConfig->isRecordingHars()) {
            // Setup a Proxy Client
            $this->Proxy = new Proxy(new Url($this->TestConfig::PROXY_URL));
        }

        // Window resolution settings
        $Resolution = new Resolution($this->TestConfig->getResWidth(), $this->TestConfig->getResHeight());

        // A class to model the ViewPort
        $ViewPort = new ViewPort(
            $this->TestConfig->getVpWidth(),
            $this->TestConfig->getVpHeight(),
            $Resolution
        );

        // WebDriver ChromeOptions
        $ChromeOptions = new ChromeOptions;
        $ChromeOptions->addOptions($this->TestConfig->getChromeOptions());

        // The WebDriver Settings, using the ChomeOptions and Resolution class
        $WebDriverSettings = $this->_buildWebDriverSettings($ChromeOptions, $Resolution);

        // The URL of where Selenium is
        $SeleniumUrl = new Url($this->TestConfig::SELENIUM_URL);

        // Start the remote session
        $this->RemoteWebDriver = WebDriverSession::init(
            $SeleniumUrl,
            $WebDriverSettings,
            $ViewPort,
            $this->TestConfig->getTimeoutSeconds()
        );

        return $this;
    }

    /**
     * Build the Webdriver session's settings
     * @param ChromeOptions $ChromeOptions
     * @param Resolution $Resolution
     * @return WebDriverSettings
     */
    private function _buildWebDriverSettings(ChromeOptions $ChromeOptions, Resolution $Resolution): WebDriverSettings
    {
        // Setup WebDriver
        $WebDriverSettings = new WebDriverSettings($ChromeOptions, $Resolution);
        $WebDriverSettings->setCapability('pageLoadStrategy', 'none');

        if ($this->Proxy instanceof Proxy) {
            // Apply a proxy
            $WebDriverSettings->setCapability(WebDriverCapabilityType::PROXY, [
                'proxyType' => 'manual',
                'httpProxy' => $this->Proxy->getClient()->getUrl(),
                'sslProxy' => $this->Proxy->getClient()->getUrl(),
//                'noProxy' =>
            ]);
        }

        return $WebDriverSettings;
    }

    /**
     * Takes a screenshot and stores it somewhere useful
     */
    public function takeScreenshot(): void
    {
        $file_location = Utils::generateFileLocation(
            __DIR__ . '/../../output/screenshots/',
            $this->TestConfig->getTestIdentifier(),
            'jpg'
        );

        $this->getRemoteWebDriver()->takeScreenshot($file_location);
    }

    /**
     * Takes a HAR and stores it somewhere useful
     */
    public function storeHar(): void
    {
        $file_location = Utils::generateFileLocation(
            __DIR__ . '/../../output/hars/',
            $this->TestConfig->getTestIdentifier(),
            'json'
        );

        $this->getProxy()->storeHar($file_location);
    }

}
