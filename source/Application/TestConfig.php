<?php

declare(strict_types=1);

namespace Application;

class TestConfig
{

    public const PROXY_URL = 'http://localhost:8080';
    public const SELENIUM_URL = 'http://localhost:4444/wd/hub';

    /**
     * @var array
     */
    public array $arg_store = [];

    /**
     * @var bool
     */
    protected bool $recording_hars = false;

    /**
     * @var bool
     */
    protected bool $recording_screenshots = false;

    /**
     * @var int
     */
    protected int $timeout_seconds = 45;

    /**
     * @var int
     */
    protected int $res_width = 1920;

    /**
     * @var int
     */
    protected int $res_height = 1080;

    /**
     * @var int
     */
    protected int $vp_width = 1440;

    /**
     * @var int
     */
    protected int $vp_height = 900;

    /**
     * @var string
     */
    protected string $test_identifier = '';

    /**
     * @var array
     */
    protected array $arrChromeOptions = [];

    /**
     * @param array $arguments
     * @return TestConfig
     */
    public static function __initFromArgs(array $arguments): TestConfig
    {
        $TestConfig = new static();

        foreach ($arguments as $arg) {
            if (!str_contains($arg, '--')) {
                continue;
            }

            $arg = str_replace('--', '', $arg);

            // Some arguments will have values to set, others won't
            $key = $arg;
            $val = null;
            if (str_contains($arg, '=')) {
                list($key, $val) = explode('=', $arg);
                $TestConfig->arg_store[$key] = $val;
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
                            if (is_numeric($val)) {
                                $val = (int) $val;
                            }
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
     * @return TestConfig
     */
    public function setRecordingHars(bool $recording_hars): TestConfig
    {
        $this->recording_hars = $recording_hars;
        return $this;
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
     * @return TestConfig
     */
    public function setRecordingScreenshots(bool $recording_screenshots): TestConfig
    {
        $this->recording_screenshots = $recording_screenshots;
        return $this;
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
     * @return TestConfig
     */
    public function setTimeoutSeconds(int $timeout_seconds): TestConfig
    {
        $this->timeout_seconds = $timeout_seconds;
        return $this;
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
     * @return TestConfig
     */
    public function setResWidth(int $res_width): TestConfig
    {
        $this->res_width = $res_width;
        return $this;
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
     * @return TestConfig
     */
    public function setResHeight(int $res_height): TestConfig
    {
        $this->res_height = $res_height;
        return $this;
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
     * @return TestConfig
     */
    public function setVpWidth(int $vp_width): TestConfig
    {
        $this->vp_width = $vp_width;
        return $this;
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
     * @return TestConfig
     */
    public function setVpHeight(int $vp_height): TestConfig
    {
        $this->vp_height = $vp_height;
        return $this;
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
     * @return TestConfig
     */
    public function setTestIdentifier(string $test_identifier): TestConfig
    {
        $this->test_identifier = $test_identifier;
        return $this;
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
     * @return TestConfig
     */
    public function addChromeOption(string $option): TestConfig
    {
        $this->arrChromeOptions[] = $option;
        return $this;
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
