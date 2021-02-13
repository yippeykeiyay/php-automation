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

}
