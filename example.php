<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Application\Library\Utils;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

// @TODO create a config class to send to the TestStrap construct
// @TODO make the HAR and proxy optional
// @TODO add ability to set things from the command line

$Test = new Application\TestStrap();

/*
 * Run the actual test
 */
try {
    $Test->buildEnvironment();

    Utils::out("Opening a HAR");
    $Test->getProxy()->newHar('https://www.tyler-architect.co.uk/');

    Utils::out("Loading page");
    $Test->getRemoteWebDriver()->get('https://www.tyler-architect.co.uk/');
    Utils::rest(1);

    // Wait for the iFrame to load
    Utils::out("Waiting for the something to be visible");
    $Test->getRemoteWebDriver()->wait($Test->timeout_seconds)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::cssSelector('figure.logo')
        )
    );

    // Print the title and URI of the current page
    Utils::out("The title is {$Test->getRemoteWebDriver()->getTitle()}");
    Utils::out("The current URI is {$Test->getRemoteWebDriver()->getCurrentURL()}");

} catch (Exception $e) {
    Utils::out("Running Error! {$e->getMessage()}");
    //    print_r($e);

} finally {
    // Store the HAR
    $path = $Test->getProxy()->storeHar(__DIR__ . '/output/hars/', 'tyler-architect');
    if ($path) {
        Utils::out($path);
    }
}

