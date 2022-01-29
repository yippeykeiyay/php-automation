# Prerequisites

 * [Brew](https://brew.sh/)
 * Composer - `brew install composer`
 * PHP 7.4
    * I use brew to manage PHP versions - `brew install php@7.4` then `brew unlink php@<version>` and `brew link php@7.4` to install 7.4 then replace whichever version you have with 7.4
 * [Java](https://www.java.com/en/)


# Install Things

## Dependencies
Run this from the repo root:
```
composer install
```

## Chrome & Chromedriver
Install Chrome, I mean, you should have this already to be fair!

Then install Chromedriver - on Mac use Brew by running:
```
brew install chromedriver
```

## Selenium
Download and extract the latest stable version of [Selenium Server Standalone](https://github.com/SeleniumHQ/selenium/releases/download/selenium-3.141.59/selenium-server-standalone-3.141.59.jar) (v3.141.59 at time of writing) into `resources/selenium-server-standalone/<version>`. If you're feeling spicy go for the latest Selenium 4 Alpha release...

## BrowserMob
Download and extract the [BrowserMob Proxy](http://bmp.lightbody.net/) zip (v2.1.4 at time of writing) into `resources/browsermob-proxy/<version>`.

## BrowserUP
Optionally, if you want to try out BrowserUp Proxy then there's a resource folder for that.


# Start Servers

## Selenium
Open a Terminal tab and run the following...the first is an example of how to get to the location to run the `java` command:
```
cd ~/Documents/GitHub/php-automation/resources/selenium/3.141.59
java -jar selenium-server-standalone-3.141.59.jar
```

This will launch a Selenium server used for orchestrating the tests in Chrome. It'll be running on [http://localhost:4444/wd/hub](http://localhost:4444/wd/hub).

**The terminal session will still be open - leave it like this.**

## BrowserMob
**In a new Terminal tab** run the following...again, the first command is an example of how to get to the right location:
```
cd ~/Documents/GitHub/php-automation/resources/browsermob-proxy/2.1.4
sh bin/browsermob-proxy -port 8080 -proxyPortRange 8081-8281 -ttl 180
```

This launches a server that is then used for launching proxy instances for recording HAR files. The server will be running on port 8080 and can launch proxies on ports 8081 to 8281 that have a TTL of 180 seconds so that the pool can be reused.

**The terminal session will still be open - leave it like this.**


# Test

To test the setup, run my example script:
```
php example.php --recording_hars --recording_screenshots --add-option=headless --test_identifier=tyler-architects
```
