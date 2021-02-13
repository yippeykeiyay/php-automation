<?php

use Application\Library\ChromeOptions;
use Application\Library\Proxy;
use Application\Library\Resolution;
use Application\Library\Utils;
use Application\Library\ViewPort;
use Application\Library\WebDriverSession;
use Application\Library\WebDriverSettings;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use RapidSpike\Targets\Url;

require_once(__DIR__ . '/vendor/autoload.php');
date_default_timezone_set('UTC');
$start = microtime(true);

// Servers
$ProxyUrl = new Url('http://localhost:8080');
$SeleniumUrl = new Url('http://localhost:4444/wd/hub');

// Timeouts
$timeout_seconds = 45;

// Window/Resolution settings
$Resolution = new Resolution(1920, 1080); /* your screen res */
$ViewPort = new ViewPort(1440, 900, $Resolution); /* desired browser viewport */

// Setup a Proxy Client
$Proxy = new Proxy($ProxyUrl);

// Setup Chrome
// Add 'headless' to the `addOptions()` function in order to prevent Chrome opening
$ChromeOptions = (new ChromeOptions)->addOption();

// Setup WebDriver
$WebDriverSettings = new WebDriverSettings($ChromeOptions, $Resolution);

// Apply a proxy
$WebDriverSettings->setCapability(WebDriverCapabilityType::PROXY, [
    'proxyType' => 'manual',
    'httpProxy' => $Proxy->getClient()->url,
    'sslProxy' => $Proxy->getClient()->url
]);

// Start the remote session
$WebDriverSession = WebDriverSession::generate($SeleniumUrl, $WebDriverSettings, $ViewPort, $timeout_seconds);

/*
 * Run the actual test
 */
try {
    Utils::out("Opening a HAR");
    $Proxy->newHar('https://www.tyler-architect.co.uk/');

    Utils::out("Loading page");
    $WebDriverSession->get('https://www.tyler-architect.co.uk/');
    Utils::rest(1);

    // Wait for the iFrame to load
    Utils::out("Waiting for the something to be visible");
    $WebDriverSession->wait($timeout_seconds)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::cssSelector('figure.logo')
        )
    );

    // Print the title and URI of the current page
    Utils::out("The title is {$WebDriverSession->getTitle()}");
    Utils::out("The current URI is {$WebDriverSession->getCurrentURL()}");

} catch (Exception $e) {
    Utils::out("Running Error! {$e->getMessage()}");
    //    print_r($e);

} finally {
    // Store the HAR
    $path = $Proxy->storeHar(__DIR__ . '/output/hars/', 'tyler-architect');
    if ($path) {
        Utils::out($path);
    }

    // Close the browser
    $WebDriverSession->quit();
}

$run_time_s = round((microtime(true) - $start), 3, PHP_ROUND_HALF_UP);
Utils::out("Completed in {$run_time_s} seconds");
exit;
