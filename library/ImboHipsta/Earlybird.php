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
 * Earlybird transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
class Earlybird extends Transformation implements TransformationInterface {
    /**
     * {@inheritdoc}
     */
    public function applyToImage(Image $image) {
        try {
            $imagick = $this->getImagick();
            $imagick->readImageBlob($image->getBlob());

            $imagick->modulateImage(100, 68, 101);
            $imagick->gammaImage(1.19);

            $range      = $imagick->getQuantumRange()['quantumRangeLong'];
            $blackPoint = 0 - round((27 / 255) * $range);

            $imagick->levelImage(0, 1, $range, Imagick::CHANNEL_RED);
            $imagick->levelImage($blackPoint, 1, $range, Imagick::CHANNEL_RED);

            $image->setBlob($imagick->getImageBlob());
        } catch (ImagickException $e) {
            throw new TransformationException($e->getMessage(), 400, $e);
        }
    }
}