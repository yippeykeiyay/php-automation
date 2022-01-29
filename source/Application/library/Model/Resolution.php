<?php

declare(strict_types=1);

namespace Application\Library\Model;

use Application\Library\Utils;

/**
 * Class Resolution
 * @package Application\Library\Model
 */
class Resolution
{

    /**
     * @var int
     */
    public int $width;

    /**
     * @var int
     */
    public int $height;

    /**
     * Resolution constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

        Utils::out("Resolution width: $this->width, height: $this->height");
    }

    /**
     * Turn the resolution setting into a nicely formatted string
     * @return string
     */
    public function asString(): string
    {
        return sprintf("%sx%s", $this->width, $this->height);
    }

}
