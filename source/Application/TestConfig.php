<?php

namespace Application;

class TestConfig
{

    const PROXY_URL = 'http://localhost:8080';
    const SELENIUM_URL = 'http://localhost:4444/wd/hub';

    /**
     * @var bool
     */
    protected $recording_hars = false;

    /**
     * @var bool
     */
    protected $recording_screenshots = false;

    /**
     * @var int
     */
    protected $timeout_seconds = 45;

    /**
     * @var int
     */
    protected $res_width = 1920;

    /**
     * @var int
     */
    protected $res_height = 1080;

    /**
     * @var int
     */
    protected $vp_width = 1440;

    /**
     * @var int
     */
    protected $vp_height = 900;

    /**
     * @var string
     */
    protected $test_identifier = '';

    /**
     * @var array
     */
    protected $arrChromeOptions = [];

    /**
     * @return TestConfig
     */
    public static function __initFromArgs(array $arguments): TestConfig
    {
        $TestConfig = new static();

        foreach ($arguments as $arg) {
            if (strpos($arg, '--') === false) {
                continue;
            }

            $arg = str_replace('--', '', $arg);

            // Some arguments will have values to set, others won't
            $key = $arg;
            $val = null;
            if (strpos($arg, '=') !== false) {
                list($key, $val) = explode('=', $arg);
            }

            if (property_exists($TestConfig, $key) || $key === 'add-option') {
                switch ($key) {
                    case 'recording_hars':
                        // Turn on HARs (proxy)
                        $TestConfig->setRecordingHars(true);
                        break;
                    case 'recording_screenshots':
                        // Turn on screenshots
                        $TestConfig->setRecordingScreenshots(true);
                        break;
                    case 'add-option':
                        // Chrome option
                        $TestConfig->addChromeOption($val);
                        break;
                    default:
                        // Dynamically handle the other arguments
                        $method = self::transformStringToMethod($key);
                        if (method_exists($TestConfig, $method)) {
                            $TestConfig->{$method}($val);
                        }
                }
            }
        }

        return $TestConfig;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function transformStringToMethod(string $string): string
    {
        return "set" . str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    /**
     * @return bool
     */
    public function isRecordingHars(): bool
    {
        return $this->recording_hars;
    }

    /**
     * @param bool $recording_hars
     */
    public function setRecordingHars(bool $recording_hars): void
    {
        $this->recording_hars = $recording_hars;
    }

    /**
     * @return bool
     */
    public function isRecordingScreenshots(): bool
    {
        return $this->recording_screenshots;
    }

    /**
     * @param bool $recording_screenshots
     */
    public function setRecordingScreenshots(bool $recording_screenshots): void
    {
        $this->recording_screenshots = $recording_screenshots;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeout_seconds;
    }

    /**
     * @param int $timeout_seconds
     */
    public function setTimeoutSeconds(int $timeout_seconds): void
    {
        $this->timeout_seconds = $timeout_seconds;
    }

    /**
     * @return int
     */
    public function getResWidth(): int
    {
        return $this->res_width;
    }

    /**
     * @param int $res_width
     */
    public function setResWidth(int $res_width): void
    {
        $this->res_width = $res_width;
    }

    /**
     * @return int
     */
    public function getResHeight(): int
    {
        return $this->res_height;
    }

    /**
     * @param int $res_height
     */
    public function setResHeight(int $res_height): void
    {
        $this->res_height = $res_height;
    }

    /**
     * @return int
     */
    public function getVpWidth(): int
    {
        return $this->vp_width;
    }

    /**
     * @param int $vp_width
     */
    public function setVpWidth(int $vp_width): void
    {
        $this->vp_width = $vp_width;
    }

    /**
     * @return int
     */
    public function getVpHeight(): int
    {
        return $this->vp_height;
    }

    /**
     * @param int $vp_height
     */
    public function setVpHeight(int $vp_height): void
    {
        $this->vp_height = $vp_height;
    }

    /**
     * @return string
     */
    public function getTestIdentifier(): string
    {
        return $this->test_identifier;
    }

    /**
     * @param string $test_identifier
     */
    public function setTestIdentifier(string $test_identifier): void
    {
        $this->test_identifier = $test_identifier;
    }

    /**
     * @return array
     */
    public function getChromeOptions(): array
    {
        return $this->arrChromeOptions;
    }

    /**
     * @param string $option
     */
    public function addChromeOption(string $option): void
    {
        $this->arrChromeOptions[] = $option;
    }

    /**
     * @param int $flags
     * @return string
     */
    public function toJson(int $flags): string
    {
        return json_encode(get_object_vars($this), $flags);
    }

}
