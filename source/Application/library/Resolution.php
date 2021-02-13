<?php

namespace Application\Library;

/**
 * Class Resolution
 * @package Application\Library
 */
class Resolution
{

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * Resolution constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

        Utils::out("Resolution width: {$this->width}, height: {$this->height}");
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        return sprintf("%sx%s", $this->width, $this->height);
    }

}
