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
    ImagickException;

/**
 * Earlybird transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
class Earlybird extends Transformation implements ListenerInterface {
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            'image.transformation.earlybird' => 'transform',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function transform(EventInterface $event) {
        try {
            $this->imagick->modulateImage(100, 68, 101);
            $this->imagick->gammaImage(1.19);

            $range      = $this->imagick->getQuantumRange()['quantumRangeLong'];
            $blackPoint = 0 - round((27 / 255) * $range);

            $this->imagick->levelImage(0, 1, $range, Imagick::CHANNEL_RED);
            $this->imagick->levelImage($blackPoint, 1, $range, Imagick::CHANNEL_RED);

            $event->getArgument('image')->hasBeenTransformed(true);
        } catch (ImagickException $e) {
            throw new TransformationException($e->getMessage(), 400, $e);
        }
    }
}