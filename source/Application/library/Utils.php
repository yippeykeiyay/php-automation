<?php

declare(strict_types=1);

namespace Application\Library;

/**
 * Class Utils
 * @package Application\Library
 */
class Utils
{

    /**
     * Log something
     * @param string $msg
     */
    public static function out(string $msg): void
    {
        echo $msg, PHP_EOL;
    }

    /**
     * Pause for a time
     * @param int $time
     */
    public static function rest(int $time): void
    {
        Utils::out("Sleeping {$time}");
        sleep($time);
    }

    /**
     * Generate a file location
     * @param string $file_directory
     * @param string $identifier
     * @param string $type
     * @return string
     */
    public static function generateFileLocation(string $file_directory, string $identifier, string $type): string
    {
        return sprintf("%s%s-%s.%s", $file_directory, $identifier, date('Ymd-His'), $type);
    }
}
