<?php
/**
 * This file is part of the imbo-hipsta package.
 *
 * (c) Espen Hovlandsdal <espen@hovlandsdal.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImboHipsta;

use Imbo\Model\Image,
    Imbo\Exception\TransformationException,
    Imbo\Image\Transformation\TransformationInterface,
    Imagick,
    ImagickException;

/**
 * Inkwell transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
class Inkwell extends Transformation implements TransformationInterface {
    /**
     * {@inheritdoc}
     */
    public function applyToImage(Image $image) {
        try {
            $imagick = $this->getImagick();
            $imagick->readImageBlob($image->getBlob());

            $imagick->modulateImage(100, 0, 100);

            $overlay = new Imagick();
            $overlay->newPseudoImage(1, 1000, 'gradient:');
            $overlay->rotateImage('#fff', 90);
            $overlay->sigmoidalContrastImage(true, 1.6, 50);
            $overlay->sigmoidalContrastImage(false, 0.333333333, 0);

            $imagick->clutImage($overlay);

            $image->setBlob($imagick->getImageBlob());
        } catch (ImagickException $e) {
            throw new TransformationException($e->getMessage(), 400, $e);
        }
    }
}