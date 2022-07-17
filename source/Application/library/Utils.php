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
     * @param string|array $msg
     */
    public static function out(string|array $msg, $newLine = true): void
    {
        if (is_array($msg)) {
            print_r($msg);
        } else {
            echo $msg;
        }

        if ($newLine) {
            echo PHP_EOL;
        }
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
        return sprintf(
            "%s%s-%s.%s",
            $file_directory,
            $identifier,
            date('Ymd-His'),
            $type
        );
    }
}
