<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Application\Library\Utils;
use Facebook\WebDriver\{
    WebDriverBy,
    WebDriverElement,
    WebDriverExpectedCondition
};

$TestConfig = Application\TestConfig::__initFromArgs($argv);
$TestConfig->setTestIdentifier('random-product-route-one');

$TestStrap = new Application\TestStrap($TestConfig);

try {
    $TestStrap->buildEnvironment();

    $WebDriver = $TestStrap->getRemoteWebDriver();
    $WebDriver->get('https://www.twoseasons.co.uk/collections/tees'); // <----- CHANGE ME ------

    for ($i=1; $i < 6; $i++) {
        Utils::out("{$i}. loading list");

        // Wait for the items to load
        $WebDriver->wait($TestConfig->getTimeoutSeconds())->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector(
                    '.boost-pfs-filter-products a' // <----- CHANGE ME ------
                )
            )
        );

        sleep(2);
        Utils::out("\t page loaded");

        // Get ALL the elements
        $elements = $WebDriver->findElements(
            WebDriverBy::cssSelector(
                '.boost-pfs-filter-products a' // <----- CHANGE ME ------
            )
        );

        // Count them and define a null var for the computing element key to be kept
        $elementCount = count($elements);
        $elementKey = null;

        Utils::out("\t total elements: {$elementCount}");

        // Compute a random key to use and ensure it's not greater or equal than the total
        while ($elementKey === null || $elementKey >= $elementCount) {
            $elementKey = floor((random_int(0,1000) / 1000) * $elementCount);
        }

        // Is the element real?
        if (!isset($elements[$elementKey])) {
            throw new Exception('Invalid element key');
        }

        // Logging purposes only
        $linkText = $elements[$elementKey]->getText();
        Utils::out("\t clicking on element {$elementKey}: {$linkText}");

        // Click on the element
        $elements[$elementKey]->click();

        Utils::out("\t loading product");

        // Wait for the product to load
        $WebDriver->wait($TestConfig->getTimeoutSeconds())->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector(
                    '.add-to-cart' // <----- CHANGE ME ------
                )
            )
        );
        sleep(2);

        Utils::out("\t returning to list");
        $WebDriver->navigate()->back();
    }


    sleep(2);
    echo PHP_EOL;
} catch (Exception $e) {
    Utils::out("Running Error! {$e->getMessage()}");
    exit(1);

} finally {
    // Store a HAR?
    // Take a screenshot?
    $TestStrap->takeScreenshot();
}