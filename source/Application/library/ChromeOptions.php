<?php

namespace Application\Library;

use Facebook\WebDriver\Chrome\ChromeOptions as __ChromeOptions;

/**
 * Class ChromeOptions
 * @package Application\Library
 */
class ChromeOptions
{

    /**
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
     * @return __ChromeOptions
     */
    public function model(): __ChromeOptions
    {
        try {
            $ChromeOptions = new __ChromeOptions();
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
