<?php

require_once('../vendor/autoload.php');
date_default_timezone_set('UTC');
$start = microtime(true);

// Servers
$ProxyUrl = new RapidSpike\Targets\Url('http://localhost:8080');
$SeleniumUrl = new RapidSpike\Targets\Url('http://localhost:4444/wd/hub');

// Timeouts
$timeout_seconds = 45;

// Window/Resolution settings
$Resolution = new Application\Library\Resolution(1920, 1080); /* your screen res */
$ViewPort = new Application\Library\ViewPort(1440, 900, $Resolution); /* desired browser viewport */

// Setup a Proxy Client
$Proxy = new Application\Library\Proxy($ProxyUrl);

// Setup Chrome
// Add 'headless' to the `addOptions()` function in order to prevent Chrome opening
$ChromeOptions = (new Application\Library\ChromeOptions)->addOption();

// Setup WebDriver
$WebDriverSettings = new Application\Library\WebDriverSettings($ChromeOptions, $Resolution);

// Apply a proxy
$WebDriverSettings->setCapability(Facebook\WebDriver\Remote\WebDriverCapabilityType::PROXY, [
    'proxyType' => 'manual',
    'httpProxy' => $Proxy->getClient()->url,
    'sslProxy' => $Proxy->getClient()->url
]);

// Start the remote session
$WebDriverSession = Application\Library\WebDriverSession::generate($SeleniumUrl, $WebDriverSettings, $ViewPort, $timeout_seconds);

/*
 * Run the actual test
 */
try {
    Application\Library\Utils::out("Opening a HAR");
    $Proxy->newHar('https://www.tyler-architect.co.uk/');

    Application\Library\Utils::out("Loading page");
    $WebDriverSession->get('https://www.tyler-architect.co.uk/');
    Application\Library\Utils::rest(1);

    // Wait for the iFrame to load
    Application\Library\Utils::out("Waiting for the something to be visible");
    $WebDriverSession->wait($timeout_seconds)->until(
        Facebook\WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(
            Facebook\WebDriver\WebDriverBy::cssSelector('figure.logo')
        )
    );

    // Print the title and URI of the current page
    Application\Library\Utils::out("The title is {$WebDriverSession->getTitle()}");
    Application\Library\Utils::out("The current URI is {$WebDriverSession->getCurrentURL()}");

} catch (Exception $e) {
    Application\Library\Utils::out("Running Error! {$e->getMessage()}");
    //    print_r($e);

} finally {
    // Store the HAR
    $path = $Proxy->storeHar(__DIR__ . '/../output/', 'tyler-architect');
    if ($path) {
        \Application\Library\Utils::out($path);
    }

    // Close the browser
    $WebDriverSession->quit();
}

$run_time_s = round((microtime(true) - $start), 3, PHP_ROUND_HALF_UP);
Application\Library\Utils::out("Completed in {$run_time_s} seconds");
exit;
