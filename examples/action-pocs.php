<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Application\Library\Utils;
use Facebook\WebDriver\{
    WebDriverBy, 
    WebDriverElement, 
    WebDriverExpectedCondition, 
    WebDriverKeys, 
    WebDriverSelect,
    WebDriverDimension
};

$TestConfig = Application\TestConfig::__initFromArgs($argv);
$TestStrap = new Application\TestStrap($TestConfig);

try {
    $TestStrap->buildEnvironment();

    $WebDriver = $TestStrap->getRemoteWebDriver();

    Utils::out("loading pages");
    $WebDriver->get('https://automationtesting.co.uk/actions.html');

    sleep(2);
} catch (Exception $e) {
    Utils::out("Running Error! {$e->getMessage()}");
    exit(1);

} finally {
    // Store the HAR
    if (true === $TestConfig->isRecordingHars()) {
        $path = $TestStrap->storeHar();
        if ($path) {
            Utils::out($path);
        }
    }
}