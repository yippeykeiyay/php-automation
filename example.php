<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Application\Library\Utils;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$TestConfig = Application\TestConfig::__initFromArgs($argv);
$TestStrap = new Application\TestStrap($TestConfig);

/*
 * Run the actual test
 */
try {
    $TestStrap->buildEnvironment();

    if (true === $TestConfig->isRecordingHars()) {
        Utils::out("Opening a HAR");
        $TestStrap->getProxy()->newHar('https://www.tyler-architect.co.uk/');
    }

    Utils::out("Loading page");
    $TestStrap->getRemoteWebDriver()->get('https://www.tyler-architect.co.uk/');

    // Wait for something to load
    Utils::out("Waiting for the something to be visible");
    $TestStrap->getRemoteWebDriver()->wait($TestConfig->getTimeoutSeconds())->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::cssSelector('figure.logo')
        )
    );

    if (true === $TestConfig->isRecordingScreenshots()) {
        // Take a screenshot
        $TestStrap->takeScreenshot();
    }

    // Print the title and URI of the current page
    Utils::out("The title is {$TestStrap->getRemoteWebDriver()->getTitle()}");
    Utils::out("The current URI is {$TestStrap->getRemoteWebDriver()->getCurrentURL()}");

} catch (Exception $e) {
    Utils::out("Running Error! {$e->getMessage()}");

} finally {
    // Store the HAR
    if (true === $TestConfig->isRecordingHars()) {
        $path = $TestStrap->storeHar();
        if ($path) {
            Utils::out($path);
        }
    }
}

