<?php

namespace Application\Library;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions as WDChromeOptions;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * Class WebDriverSettings
 * @package Application\Library
 */
class WebDriverSettings
{

    /**
     * @var ChromeOptions
     */
    private $ChromeOptions;

    /**
     * @var Resolution
     */
    private $Resolution;

    /**
     * @var DesiredCapabilities
     */
    private $WebDriverSettings;

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
     * @param mixed $name
     * @param mixed $value
     */
    public function setCapability($name, $value)
    {
        $this->WebDriverSettings->setCapability($name, $value);
    }

    /**
     * @return DesiredCapabilities
     */
    public function __init(): DesiredCapabilities
    {
        try {
            // Add Chrome Options
            $this->WebDriverSettings->setCapability(WDChromeOptions::CAPABILITY, $this->ChromeOptions->model());

            // Add some session settings
            $this->WebDriverSettings->setCapability('screenResolution', $this->Resolution->asString());
            $this->WebDriverSettings->setCapability('acceptInsecureCerts', true);
            $this->WebDriverSettings->setCapability('unexpectedAlertBehaviour', 'dismiss');
            $this->WebDriverSettings->setCapability(WebDriverCapabilityType::ACCEPT_SSL_CERTS, true);
            $this->WebDriverSettings->setCapability(WebDriverCapabilityType::JAVASCRIPT_ENABLED, true);

            Utils::out('WebDriver settings generated');
        } catch (\Exception $e) {
            Utils::out("WebDriver Settings Error! {$e->getMessage()}");
            exit;
        }

        return $this->WebDriverSettings;
    }

}
