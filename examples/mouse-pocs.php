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
    $WebDriver->get('https://automationtesting.co.uk/mouse.html');

    sleep(1);

    $locations = [".out.overout", ".out.enterleave"];

    for ($i=0; $i < 15; $i++) {
        $selector = $locations[mt_rand(0, count($locations)-1)];
        Utils::out("moving to '{$selector}'");

        $WebDriver->action()
            ->moveToElement(
                $WebDriver->findElement(
                    WebDriverBy::cssSelector($selector)
                )
            )->perform();

        sleep(0.5);

        $WebDriver->action()
            ->moveToElement(
                $WebDriver->findElement(
                    WebDriverBy::cssSelector("#content")
                )
            )->perform();

        sleep(0.5);
    }

    sleep(1);
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