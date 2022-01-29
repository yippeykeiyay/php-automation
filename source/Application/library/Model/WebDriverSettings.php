<?php

declare(strict_types=1);

namespace Application\Library\Model;

use Application\Library\Utils;
use Facebook\WebDriver\Remote\{DesiredCapabilities, WebDriverCapabilityType};
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions as WebDriverChromeOptions;

/**
 * Class WebDriverSettings
 * @package Application\Library\Model
 */
class WebDriverSettings
{

    /**
     * @var ChromeOptions
     */
    private ChromeOptions $ChromeOptions;

    /**
     * @var Resolution
     */
    private Resolution $Resolution;

    /**
     * @var DesiredCapabilities
     */
    private DesiredCapabilities $WebDriverSettings;

    /**
     * WebDriverSettings constructor.
     * @param ChromeOptions $ChromeOptions
     * @param Resolution $Resolution
     */
    public function __construct(ChromeOptions $ChromeOptions, Resolution $Resolution)
    {
        $this->ChromeOptions = $ChromeOptions;
        $this->Resolution = $Resolution;

        $this->WebDriverSettings = DesiredCapabilities::chrome();
    }

    /**
     * Set a capability to the settings object
     * @param mixed $name
     * @param mixed $value
     */
    public function setCapability(mixed $name, mixed $value): void
    {
        $this->WebDriverSettings->setCapability($name, $value);
    }

    /**
     * Instantiates and returns a new instance of the Desired Capabilities with the required settings
     * @return DesiredCapabilities
     */
    public function generateDesiredCapabilities(): DesiredCapabilities
    {
        try {
            // Add Chrome Options
            $this->WebDriverSettings->setCapability(WebDriverChromeOptions::CAPABILITY_W3C, $this->ChromeOptions->model());

            // Add some session settings
            $this->WebDriverSettings->setCapability('screenResolution', $this->Resolution->asString());
            $this->WebDriverSettings->setCapability('acceptInsecureCerts', true);
            $this->WebDriverSettings->setCapability('unexpectedAlertBehaviour', 'dismiss');
            $this->WebDriverSettings->setCapability(WebDriverCapabilityType::ACCEPT_SSL_CERTS, true);
            $this->WebDriverSettings->setCapability(WebDriverCapabilityType::JAVASCRIPT_ENABLED, true);

            Utils::out('WebDriver settings generated');
        } catch (Exception $e) {
            Utils::out("WebDriver Settings Error! {$e->getMessage()}");
            exit(1);
        }

        return $this->WebDriverSettings;
    }

}
