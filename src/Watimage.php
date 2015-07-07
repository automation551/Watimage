<?php
namespace Elboletaire\Watimage;

use Exception;

/**
 *
 * @author Òscar Casajuana Alonso <elboletaire@underave.net>
 * @version 1.0
 * @link https://github.com/elboletaire/Watimage
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */
class Watimage
{
    /**
     * Image handler.
     *
     * @var Image
     */
    protected $image;

    /**
     * Watermark handler.
     *
     * @var Watermark False if no watermark set, Watermark otherwise.
     */
    protected $watermark = false;

    /**
     * Any error returned by the class will be stored here
     * @public array $errors
     */
    public $errors = [];

    public function __construct($file = null, $watermark = null)
    {
        $this->image = new Image($file);
        $this->watermark = new Watermark($watermark);
    }

    /**
     *  Sets image and (optionally) its options
     *
     *  @param mixed $filename Filename string or array containing both filename and quality
     *  @return Watimage
     *  @throws Exception
     */
    public function setImage($filename)
    {
        try {
            $this->image->load($filename);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     * Sets quality for gif and jpg files.
     *
     * @param int $quality A value from 0 (zero quality) to 100 (max quality).
     */
    public function setQuality($quality)
    {
        try {
            $this->image->setQuality($quality);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     * Sets compression for png files.
     *
     * @param int $compression A value from 0 (no compression, not recommended) to 9.
     */
    public function setCompression($compression)
    {
        try {
            $this->image->setQuality($compression);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     * Set watermark and (optionally) its options.
     *
     * @param mixed $options [optional] you can set the watermark without options
     *              or you can set an array of options like:
     *              $options = array(
     *                  'file'     => 'watermark.png',
     *                  'position' => 'bottom right', // default
     *                  'margin'   => array('20', '10') // 0 by default
     *              );
     * @return true on success; false on failure
     */
    public function setWatermark($options = array())
    {
        try {
            if (is_array($options)) {
                $file = $options['file'];

                $this->watermark->load($file, $options);
            } else {
                $this->watermark->load($options);
            }

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     *  Resizes the image
     *  @param array $options = array(
     *                  'type' => 'resizemin|resizecrop|resize|crop',
     *                  'size' => array('x' => 2000, 'y' => 500)
     *              )
     *          You can also set the size without specifying x and y: array(2000, 500). Or directly 'size' => 2000 (takes 2000x2000)
     *  @return bool true on success; otherwise false
     */
    public function resize($options = array())
    {
        try {
            $this->image->resize($options['type'], $options['size']);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     * Crops an image based on specified coords and size
     * @param mixed $options = array('x' => 23, 'y' => 23, 'width' => 230, 'height' => 230)
     * @return bool success
     */
    public function crop($options = array())
    {
        try {
            $this->image->crop($options);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     *  Rotates an image
     *  @param mixed $options = array('bgcolor' => 230, 'degrees' => -90); or $options = -90; // takes bgcolor = -1 by default
     *  @return true on success; false on failure
     */
    public function rotateImage($options = array())
    {
        try {
            if (is_array($options)) {
                if (empty($options['bgcolor'])) {
                    $options['bgcolor'] = -1;
                }
                $this->image->rotate($options['degrees'], $options['bgcolor']);
            } else {
                $this->image->rotate($options);
            }

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     *  rotateImage alias
     */
    public function rotate($options = array())
    {
        return $this->rotateImage($options);
    }

    /**
     *  Applies a watermark to the image. Needs to be initialized with $this->setWatermark()
     *  @return true on success, otherwise false
     */
    public function applyWatermark()
    {
        try {
            $this->watermark->apply($this->image);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     *  Flips an image.
     *  @param string $type [optional] type of flip: horizontal / vertical / both
     *  @return true on success. Otherwise false
     */
    public function flip($type = 'horizontal')
    {
        try {
            $this->image->flip($type);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }

    /**
     *  Generates the image file.
     *  @param string $path [optional] if not specified image will be printed on screen
     *  @param string $output [optional] mime type for output image (image/png, image/gif, image/jpeg)
     *  @return true on success. Otherwise false
     */
    public function generate($path = null, $output = null)
    {
        try {
            $this->image->generate($path, $output);

            return true;
        } catch (Exception $e) {
            array_push($this->errors, $e->getMessage());

            return false;
        }
    }
}
