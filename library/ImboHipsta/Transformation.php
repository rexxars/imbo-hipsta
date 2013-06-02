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

use Imbo\Image\Transformation\Transformation as ImboTransformation,
    Imbo\Image\Transformation\TransformationInterface,
    Imagick,
    ImagickPixel;

/**
 * imbo-hipsta abstract transformation
 *
 * @author Espen Hovlandsdal <espen@hovlandsdal.com>
 */
abstract class Transformation extends ImboTransformation implements TransformationInterface {



}