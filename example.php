<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Application\Library\Utils;
use Facebook\WebDriver\{WebDriverBy, WebDriverExpectedCondition};

$TestConfig = Application\TestConfig::__initFromArgs($argv);
$TestStrap = new Application\TestStrap($TestConfig);

/*
 * Run the actual test
 */
try {
    $TestStrap->buildEnvironment();

    $start_url = 'https://www.tyler-architect.co.uk/';

    if (true === $TestConfig->isRecordingHars()) {
        Utils::out("Opening a HAR");
        $TestStrap->getProxy()->newHar($start_url);
    }

    Utils::out("Loading page");
    $TestStrap->getRemoteWebDriver()->get($start_url);

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

