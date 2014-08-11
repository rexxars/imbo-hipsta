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

use Imbo\Image\Transformation\Transformation,
    Imbo\Exception\TransformationException,
    Imbo\EventListener\ListenerInterface,
    Imbo\EventManager\EventInterface,
    Imagick,
    ImagickPixel,
    ImagickException;

/**
 * Vintage transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
class Vintage extends Transformation implements ListenerInterface {
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            'image.transformation.vintage' => 'transform',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function transform(EventInterface $event) {
        $image = $event->getArgument('image');

        try {
            // Contrast
            $this->imagick->contrastImage(1);

            // Noise
            $this->imagick->addNoiseImage(Imagick::NOISE_GAUSSIAN, Imagick::CHANNEL_GREEN);

            // Desaturate + adjust brightness
            $this->imagick->modulateImage(135, 25, 100);

            // Adjust color balance
            $this->imagick->evaluateImage(Imagick::EVALUATE_MULTIPLY, 1.1, Imagick::CHANNEL_RED);
            $this->imagick->evaluateImage(Imagick::EVALUATE_MULTIPLY, 1.02, Imagick::CHANNEL_BLUE);
            $this->imagick->evaluateImage(Imagick::EVALUATE_MULTIPLY, 1.1, Imagick::CHANNEL_GREEN);

            // Gamma
            $this->imagick->gammaImage(0.87);

            // Vignette
            $width  = $image->getWidth();
            $height = $image->getHeight();
            $size   = $height > $width ? $width / 6 : $height / 6;

            $this->imagick->setImageBackgroundColor(new ImagickPixel('black'));
            $this->imagick->vignetteImage(0, 60, 0 - $size, 0 - $size);

            // Mark as transformed
            $image->hasBeenTransformed(true);
        } catch (ImagickException $e) {
            throw new TransformationException($e->getMessage(), 400, $e);
        }
    }
}