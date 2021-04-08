<?php

declare(strict_types=1);

namespace Application\Library;

use Facebook\WebDriver\Chrome\ChromeOptions as WebDriverChromeOptions;

/**
 * Class ChromeOptions
 * @package Application\Library
 */
class ChromeOptions
{

    /**
     * Default options
     * @var string[]
     */
    private $arrOptions = [
        '--disable-dev-shm-usage',
        '--ignore-certificate-errors',
        '--test-type',
        '--log-level=3',
        '--no-first-run',
        '--allow-file-access-from-files',
        '--allow-insecure-localhost',
        '--allow-running-insecure-content',
        '--disable-infobars',
        '--disable-web-security',
        '--disable-translate',
    ];

    /**
     * Add an option either with or without the "--" at the start
     * @param string|null $option
     * @return $this
     */
    public function addOption(string $option = null): ChromeOptions
    {
        if (!empty($option)) {
            if (substr($option, 0, 2) !== '--') {
                $option = "--{$option}";
            }

            $this->arrOptions[] = $option;
        }

        return $this;
    }

    /**
     * Add a set of options
     * @param array $arrOptions
     */
    public function addOptions(array $arrOptions = []): ChromeOptions
    {
        foreach ($arrOptions as $option) {
            $this->addOption($option);
        }

        return $this;
    }

    /**
     * Basically create a proper WebDriverChromeOptions
     * object out of the options applied to this class
     * @return WebDriverChromeOptions
     */
    public function model(): WebDriverChromeOptions
    {
        try {
            $ChromeOptions = new WebDriverChromeOptions();
            $ChromeOptions->setExperimentalOption('w3c', false);
            $ChromeOptions->addArguments($this->arrOptions);

            Utils::out("Chrome options modeled");
        } catch (\Exception $e) {
            Utils::out("Chrome Settings Error! {$e->getMessage()}");
            exit;
        }

        return $ChromeOptions;
    }

}
