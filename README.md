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
Download and extract the latest stable version of [Selenium Server Standalone](https://github.com/lightbody/browsermob-proxy) (v3.141.59 at time of writing) into `resources/selenium-server-standalone/<version>`. If you're feeling spicy go for the latest Selenium 4 Alpha release...

## BrowserMob
Download and extract the [BrowserMob Proxy](https://github.com/lightbody/browsermob-proxy) package (v2.1.4 at time of writing) into `resources/browsermob-proxy/<version>`.

## BrowserUP
Optionally, if you want to try out BrowserUp Proxy then there's a resource folder for that.


# Start Servers

## Selenium
In the Selenium resource directory run this:
```
java -jar selenium-server-standalone-3.141.59.jar
```

This will launch a Selenium server used for orchestrating the tests in Chrome. It'll be running on [http://localhost:4444/wd/hub](http://localhost:4444/wd/hub). The terminal session will still be open - leave it like this.

## BrowserMob
In the BrowserMob resource directory run this:
```
sh bin/browsermob-proxy -port 8080 -proxyPortRange 8081-8281 -ttl 180
```

This launches a server that is then used for launching proxy instances for recording HAR files. The server will be running on port 8080 and can launch proxies on ports 8081 to 8281 that have a TTL of 180 seconds so that the pool can be reused.


# Test

To test the setup, run my example script:
```
php example.php
```