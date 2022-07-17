<?php

declare(strict_types=1);

namespace Application\Library\Model;

use Application\Library\Utils;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions as WebDriverChromeOptions;

/**
 * Class ChromeOptions
 * @package Application\Library\Model
 */
class ChromeOptions
{

    /**
     * Default options
     * @var string[]
     */
    private array $arrOptions = [
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
        '--user-agent=Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36'
    ];

    /**
     * Add an option either with or without the "--" at the start
     * @param string|null $option
     * @return $this
     */
    public function addOption(string $option = null): ChromeOptions
    {
        if (!empty($option)) {
            if (!str_starts_with($option, '--')) {
                $option = "--{$option}";
            }

            $this->arrOptions[] = $option;
        }

        return $this;
    }

    /**
     * @param array $arrOptions
     * @return $this
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
            $ChromeOptions->setExperimentalOption('w3c', true);
            $ChromeOptions->addArguments($this->arrOptions);

            Utils::out("Chrome options modeled");
        } catch (Exception $e) {
            Utils::out("Chrome Settings Error! {$e->getMessage()}");
            exit;
        }

        return $ChromeOptions;
    }

}
