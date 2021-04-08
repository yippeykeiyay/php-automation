<?php

declare(strict_types=1);

namespace Application\Library;

/**
 * Class ViewPort
 * @package Application\Library
 */
class ViewPort
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
     * @var int
     */
    public $pos_x = 0;

    /**
     * @var int
     */
    public $pos_y = 0;

    /**
     * ViewPort constructor.
     * @param int $width
     * @param int $height
     * @param Resolution $Resolution
     */
    public function __construct(int $width, int $height, Resolution $Resolution)
    {
        $this->width = $width;
        $this->height = $height;

        // Calculate the window position relative to the screen resolution
        if ($Resolution->height > 0 && $Resolution->width > 0) {
            $x = ($Resolution->width / 2) - ($width / 2);
            $this->pos_x = intval($x < 10 ? 0 : $x);
            $this->pos_y = 0;
        }

        Utils::out("ViewPort width: {$this->width}, height: {$this->height}");
        Utils::out("Position X: {$this->pos_x}, Y: {$this->pos_y}");
    }

}
