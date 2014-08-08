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
 * Inkwell transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
class Inkwell extends Transformation implements ListenerInterface {
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            'image.transformation.inkwell' => 'transform',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function transform(EventInterface $event) {
        try {
            $this->imagick->modulateImage(100, 0, 100);

            $overlay = new Imagick();
            $overlay->newPseudoImage(1, 1000, 'gradient:');
            $overlay->rotateImage('#fff', 90);
            $overlay->sigmoidalContrastImage(true, 1.6, 50);
            $overlay->sigmoidalContrastImage(false, 1 / 3, 0);

            $this->imagick->clutImage($overlay);

            $event->getArgument('image')->hasBeenTransformed(true);
        } catch (ImagickException $e) {
            throw new TransformationException($e->getMessage(), 400, $e);
        }
    }
}