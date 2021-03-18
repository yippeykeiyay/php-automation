<?php

namespace Application\Library;

/**
 * Class Utils
 * @package Application\Library
 */
class Utils
{

    /**
     * Log something
     * @param $msg
     */
    public static function out($msg)
    {
        echo $msg, PHP_EOL;
    }

    /**
     * Pause for a time
     * @param $time
     */
    public static function rest($time)
    {
        Utils::out("Sleeping {$time}");
        sleep($time);
    }

    /**
     * @param string $file_directory
     * @param string $identifier
     * @param string $type
     * @return string
     */
    public static function genFileLocation(string $file_directory, string $identifier, string $type): string
    {
        return sprintf("%s%s-%s.%s", $file_directory, $identifier, date('Ymd-His'), $type);
    }
}
