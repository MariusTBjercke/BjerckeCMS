<?php

declare(strict_types=1);

namespace App\Service\Image;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer {
    private const MAX_WIDTH = 200;
    private const MAX_HEIGHT = 200;

    private Imagine $imagine;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->imagine = new Imagine();
    }

    /**
     * Resize image.
     *
     * @param string $filename Filename.
     * @return void
     */
    public function resize(string $filename): void {
        [$iWidth, $iHeight] = getimagesize($filename);
        $ratio = $iWidth / $iHeight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $image = $this->imagine->open($filename);

        $image->resize(new Box($width, $height))->save($filename);
    }
}
