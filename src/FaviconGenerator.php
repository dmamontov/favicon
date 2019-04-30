<?php
/**
 * Favicon Generator
 *
 * Copyright (c) 2015, Dmitry Mamontov <d.slonyara@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Dmitry Mamontov nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   favicon
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.0.0
 */

/**
 * FaviconGenerator - Class generation favicon for browsers and devices Android, Apple, Windows and display of html code. It supports a large number of settings such as margins, color, compression, three different methods of crop and screen orientation.
 *
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version   Release: 1.0.0
 * @link      https://github.com/dmamontov/favicon
 * @since     Class available since Release 1.0.0
 */
class FaviconGenerator
{
    /*
     * No compression.
     */
    const COMPRESSION_ORIGINAL = 100;

    /*
     * Low compression.
     */
    const COMPRESSION_LOW = 75;

    /*
     * High compression.
     */
    const COMPRESSION_HIGH = 50;

    /*
     * Very high compression.
     */
    const COMPRESSION_VERYHIGH = 25;

    /*
     * Crop the image centered.
     */
    const CROPMETHOD_CENTER = 0;

    /*
     * Balanced crop image.
     */
    const CROPMETHOD_BALANCED = 1;

    /*
     * Entropy crop image.
     */
    const CROPMETHOD_ENTROPY = 2;

    /*
     * Color Teal.
     */
    const COLOR_TEAL = '00aba9';

    /*
     * Color Dark Blue.
     */
    const COLOR_DARKBLUE = '2b5797';

    /*
     * Color LIght Purple.
     */
    const COLOR_LIGHTPURPLE = '9f00a7';

    /*
     * Color Dark Purple.
     */
    const COLOR_DARKPURPLE = '603cba';

    /*
     * Color Dark Red.
     */
    const COLOR_DARKRED = 'b91d47';

    /*
     * Color Dark Orange.
     */
    const COLOR_DARKORANGE = 'da532c';

    /*
     * Color Yellow.
     */
    const COLOR_YELLOW = 'ffc40d';

    /*
     * Color Green.
     */
    const COLOR_GREEN = '00a300';

    /*
     * Color Blue.
     */
    const COLOR_BLUE = '2d89ef';

    /*
     * Portrait screen orientation.
     */
    const ANDROID_PORTRAIT = 'portrait';

    /*
     * Landscape screen orientation.
     */
    const ANDROID_LANDSCAPE = 'landscape';

    /**
     * Root directory.
     * @var string
     * @access private
     */
    private $root;

    /**
     * Flag of forced re-create files.
     * @var boolean
     * @access private
     */
    private $created;

   /**
    * Settings generation.
    * @var array
    * @access private
    */
    private $settings = array();

    /**
     * Validation and installation defaults.
     * @param string $icon
     * @param boolean $created
     * @return void
     * @access public
     * @final
     */
    final public function __construct($icon = '', $created = false)
    {
        $this->created = $created;
        $this->root = php_sapi_name() == 'cli' ? __DIR__ : $_SERVER['DOCUMENT_ROOT'];
        
        if (empty($icon)) {
            $icon = "{$this->root}/favicon/.original";
        }

        if (file_exists($icon) === false) {
            throw new RuntimeException('File not found', 404);
        }
        if (class_exists('Imagick') === false) {
            throw new RuntimeException('Class Imagick not found');
        }

        if (file_exists("{$this->root}/favicon/.settings")) {
            $this->settings = json_decode(file_get_contents("{$this->root}/favicon/.settings"), true);
        } else {
            $this->settings = array(
                'compression' => self::COMPRESSION_ORIGINAL,
                'cropmethod'  => self::CROPMETHOD_CENTER
            );
        }

        if (
            file_exists("{$this->root}/favicon/.original") === false ||
            filesize($icon) != filesize("{$this->root}/favicon/.original")
        ) {
            @mkdir("{$this->root}/favicon", 0755);
            @copy($icon, "{$this->root}/favicon/.original");
            $this->created == true;
        }
    }

    /**
     * Saving settings.
     * @return void
     * @access public
     * @final
     */
    final public function __destruct()
    {
        file_put_contents("{$this->root}/favicon/.settings", json_encode($this->settings));
    }

    /**
     * Sets the compression ratio favicon.
     * @param integer $compression
     * @return boolean
     * @access public
     * @final
     */
    final public function setCompression($compression)
    {
        if (
            in_array(
                $compression,
                array(
                    self::COMPRESSION_ORIGINAL,
                    self::COMPRESSION_LOW,
                    self::COMPRESSION_HIGH,
                    self::COMPRESSION_VERYHIGH
                )
            ) == false
        ) {
            throw new RuntimeException('Unacceptable degree of compression');
        }

        if (isset($this->settings['compression']) && $this->settings['compression'] != $compression) {
            $this->created == true;
            $this->settings['compression'] = $compression;
        }

        return true;
    }

    /**
     * Gets the compression ratio favicon.
     * @return integer
     * @access public
     * @final
     */
    final public function getCompression()
    {
        return $this->settings['compression'];
    }

    /**
     * Sets the method of crop images favicon.
     * @param integer $method
     * @return boolean
     * @access public
     * @final
     */
    final public function setCropMethod($method)
    {
        if (
            in_array(
                $method,
                array(
                    self::CROPMETHOD_CENTER,
                    self::CROPMETHOD_BALANCED,
                    self::CROPMETHOD_ENTROPY
                )
            ) == false
        ) {
            throw new RuntimeException('Illegal crop method');
        }

        if (isset($this->settings['cropmethod']) && $this->settings['cropmethod'] != $method) {
            $this->created == true;
            $this->settings['cropmethod'] = $method;
        }

        return true;
    }

    /**
     * Is produced by the image crop favicon.
     * @return integer
     * @access public
     * @final
     */
    final public function getCropMethod()
    {
        return $this->settings['cropmethod'];
    }

    /**
     * Set options for generating favicon.
     *
     * array(
     *     'apple-background'    => FaviconGenerator::COLOR_BLUE,
     *     'apple-margin'        => 15,
     *     'android-background'  => FaviconGenerator::COLOR_GREEN,
     *     'android-margin'      => 15,
     *     'android-name'        => 'My app',
     *     'android-url'         => 'http://test.ru"',
     *     'android-orientation' => FaviconGenerator::ANDROID_PORTRAIT,
     *     'ms-background'       => FaviconGenerator::COLOR_GREEN,
     * )
     *
     * @param array $config
     * @return boolean
     * @access public
     * @final
     */
    final public function setConfig($config = array())
    {
        if (is_array($config) === false || count($config) < 1) {
            throw new RuntimeException('Invalid configuration');
        }

        foreach ($config as $key => $value) {
            if (
                array_key_exists($key, $this->settings) === false ||
                (array_key_exists($key, $this->settings) && $this->settings[$key] !== $value)
            ) {
                $this->created == true;
                $this->settings = array_merge($this->settings, $config);
                break;
            }
        }

        return true;
    }

    /**
     * Creates basic favicon.
     * @return boolean
     * @access public
     * @final
     */
    final public function createBasic()
    {
        foreach (array('16x16', '32x32', '96x96') as $size) {
            if ($this->created || file_exists("{$this->root}/favicon/favicon-{$size}.png") == false) {
                $image = $this->createImage($size);

                $image->writeimage("{$this->root}/favicon/favicon-{$size}.png");
            }
        }

        return true;
    }

    /**
     * Creates a favicon devices Apple.
     * @return boolean
     * @access public
     * @final
     */
    final public function createApple()
    {
        foreach (
            array('57x57', '60x60', '72x72', '76x76', '114x114', '120x120', '144x144', '152x152', '180x180')
            as $size
        ) {
            if ($this->created || file_exists("{$this->root}/favicon/apple-touch-icon-{$size}.png") == false) {
                $image = $this->createImage($size);
                $image = $this->setColorAndMargin($image, 'apple-background', 'apple-margin');

                $image->writeimage("{$this->root}/favicon/apple-touch-icon-{$size}.png");
            }
        }

        return true;
    }

    /**
     * Creates a favicon for devices Android.
     * @return boolean
     * @access public
     * @final
     */
    final public function createAndroid()
    {
        $replace = false;

        $manifest = file_exists("{$this->root}/favicon/manifest.json") ?
                            json_decode(file_get_contents("{$this->root}/favicon/manifest.json"), true) :
                            array();

        if (
            isset($this->settings['android-name']) &&
            empty($this->settings['android-name']) === false &&
            (
                isset($manifest['name']) === false ||
                (
                    isset($manifest['name']) &&
                    $manifest['name'] != $this->settings['android-name']
                )
            )
        ) {
            $replace = true;
            $manifest['name'] = $this->settings['android-name'];
        }

        if (
            isset($this->settings['android-url']) &&
            empty($this->settings['android-url']) === false &&
            (
                isset($manifest['start_url']) === false ||
                (
                    isset($manifest['start_url']) &&
                    $manifest['start_url'] != $this->settings['android-url']
                )
            )
        ) {
            $replace = true;
            $manifest['start_url'] = $this->settings['android-url'];
        }

        if (
            isset($this->settings['android-orientation']) &&
            empty($this->settings['android-orientation']) === false &&
            in_array(
                $this->settings['android-orientation'],
                array(self::ANDROID_LANDSCAPE, self::ANDROID_PORTRAIT)
            ) &&
            (
                isset($manifest['orientation']) === false ||
                (
                    isset($manifest['orientation']) &&
                    $manifest['orientation'] != $this->settings['android-orientation']
                )
            )
        ) {
            $replace = true;
            $manifest['display'] = 'standalone';
            $manifest['orientation'] = $this->settings['android-orientation'];
        }

        $mapDensity = array(
            '36x36' => '0.75',
            '48x48' => '1.0',
            '72x72' => '1.5',
            '96x96' => '2.0',
            '144x144' => '3.0',
            '192x192' => '4.0'
        );
        foreach (
            array('36x36', '48x48', '72x72', '96x96', '144x144', '192x192')
            as $size
        ) {
            if ($this->created || file_exists("{$this->root}/favicon/android-chrome-{$size}.png") == false) {
                $image = $this->createImage($size);
                $image = $this->setColorAndMargin($image, 'android-background', 'android-margin');

                $image->writeimage("{$this->root}/favicon/android-chrome-{$size}.png");
            }

            $manifest['icons'][] = array(
                'src'     => "/favicon/android-chrome-{$size}.png",
                'sizes'    => $size,
                'type'    => 'image/png',
                'density' => $mapDensity[$size]
            );
        }

        if ($replace && count($manifest) > 0) {
            file_put_contents("{$this->root}/favicon/manifest.json", json_encode($manifest));
        }

        return true;
    }

    /**
     * Creates a favicon for devices Microsoft.
     * @return boolean
     * @access public
     * @final
     */
    final public function createMicrosoft()
    {
        foreach (array('70x70', '144x144', '150x150', '310x310', '310x150') as $size) {
            if ($this->created || file_exists("{$this->root}/favicon/mstile-{$size}.png") == false) {
                if ($size == '310x150') {
                    $image = $this->createImage('150x150');
                    $image->borderImage(new ImagickPixel('none'), 80, 0);
                } else {
                    $image = $this->createImage($size);
                }

                $image->writeimage("{$this->root}/favicon/mstile-{$size}.png");
            }
        }

        if (file_exists("{$this->root}/favicon/browserconfig.xml") === false || $this->created) {
            $browserconfig =
"<?xml version=\"1.0\" encoding=\"utf-8\"?>
<browserconfig>
    <msapplication>
        <tile>
            <square70x70logo src=\"/favicon/mstile-70x70.png\"/>
            <square150x150logo src=\"/favicon/mstile-150x150.png\"/>
            <square310x310logo src=\"/favicon/mstile-310x310.png\"/>
            <wide310x150logo src=\"/favicon/mstile-310x150.png\"/>
            <TileColor>" .
            (
                isset($this->settings['ms-background']) ?
                "#{$this->settings['ms-background']}" :
                ''
            ) .
            "</TileColor>
        </tile>
    </msapplication>
</browserconfig>";

            file_put_contents("{$this->root}/favicon/browserconfig.xml", $browserconfig);
        }

        return true;
    }

    /**
     * It creates a favicon for all devices.
     * @return boolean
     * @access public
     * @final
     */
    final public function createAll()
    {
        $this->createBasic();
        $this->createApple();
        $this->createAndroid();
        $this->createMicrosoft();

        return true;
    }

    /**
     * Generates meta tags and links to connect favicon.
     * @return mixed
     * @access public
     * @final
     */
    final public function getHtml()
    {
        $html = '';

        foreach (array('16x16', '32x32', '96x96') as $size) {
            if (file_exists("{$this->root}/favicon/favicon-{$size}.png")) {
                $html .= "<link rel=\"icon\" type=\"image/png\" href=\"/favicon/favicon-{$size}.png\" sizes=\"{$size}\">\n";
            }
        }

        foreach (
            array('57x57', '60x60', '72x72', '76x76', '114x114', '120x120', '144x144', '152x152', '180x180')
            as $size
        ) {
            if (file_exists("{$this->root}/favicon/apple-touch-icon-{$size}.png")) {
                $html .= "<link rel=\"apple-touch-icon\" sizes=\"{$size}\" href=\"/favicon/apple-touch-icon-{$size}.png\">\n";
            }
        }

        if (file_exists("{$this->root}/favicon/android-chrome-192x192.png")) {
            $html .= "<link rel=\"icon\" type=\"image/png\" href=\"/favicon/android-chrome-192x192.png\" sizes=\"192x192\">\n";
        }
        if (file_exists("{$this->root}/favicon/manifest.json")) {
            $html .= "<link rel=\"manifest\" href=\"/favicon/manifest.json\">\n";
        }

        if (file_exists("{$this->root}/favicon/mstile-144x144.png")) {
            $html .= "<meta name=\"msapplication-TileImage\" content=\"/favicon/mstile-144x144.png\">\n";
        }
        if (isset($this->settings['ms-background'])) {
            $html .= "<meta name=\"msapplication-TileColor\" content=\"#{$this->settings['ms-background']}\">\n";
            $html .= "<meta name=\"theme-color\" content=\"#{$this->settings['ms-background']}\">\n";
        }

        return strlen($html) > 0 ? $html : false;
    }

    /**
     * It creates a favicon for all devices and generates meta tags and links to connect favicon.
     * @return mixed
     * @access public
     * @final
     */
    final public function createAllAndGetHtml()
    {
        $this->createBasic();
        $this->createApple();
        $this->createAndroid();
        $this->createMicrosoft();

        return $this->getHtml();
    }

    /**
     * Set the fill and margins of the image.
     * @param Imagick $method
     * @param string $colorKey
     * @param string $marginKey
     * @return Imagick
     * @access private
     * @final
     */
    final private function setColorAndMargin(Imagick $image, $colorKey = '', $marginKey = '')
    {
        if (isset($this->settings[$colorKey]) && empty($this->settings[$colorKey]) === false) {
            $image->setImageBackgroundColor("#{$this->settings[$colorKey]}");

            if (
                isset($this->settings[$marginKey]) &&
                empty($this->settings[$marginKey]) === false &&
                (int) $this->settings[$marginKey] <= 15
            ) {
                $source = $image->getImageGeometry();
                $image->resizeImage(
                    $source['width'] - ($this->settings[$marginKey] * 2),
                    $source['height'] - ($this->settings[$marginKey] * 2),
                    Imagick::FILTER_CUBIC,
                    1
                );
                $image->borderImage(
                    "#{$this->settings[$colorKey]}",
                    $this->settings[$marginKey],
                    $this->settings[$marginKey]
                );
            }
        }

        return $image;
    }

    /**
     * It creates an image of a given size.
     * @param string $size
     * @return Imagick
     * @access private
     * @final
     */
    final private function createImage($size)
    {
        list($sizes['width'], $sizes['height']) = explode('x', $size);

        $original = new Imagick("{$this->root}/favicon/.original");

        $source = $original->getImageGeometry();

        if (($source['width'] / $source['height']) < ($sizes['width'] / $sizes['height'])) {
            $scale = $source['width'] / $sizes['width'];
        } else {
            $scale = $source['height'] / $sizes['height'];
        }

        $original->setImageFormat('png');
        $original->setImageCompressionQuality($this->getCompression());

        $source['width'] = (int) ($source['width'] / $scale);
        $source['height'] = (int) ($source['height'] / $scale);

        $original->resizeImage($source['width'], $source['height'], Imagick::FILTER_CUBIC, 1);

        switch ($this->getCropMethod()) {
            case self::CROPMETHOD_CENTER:
                $offset = $this->getOffsetCenter($original, $sizes['width'], $sizes['height']);
                break;
            case self::CROPMETHOD_BALANCED:
                $offset = $this->getOffsetBalanced($original, $sizes['width'], $sizes['height']);
                break;
            case self::CROPMETHOD_ENTROPY:
                $offset = $this->getOffsetEntropy($original, $sizes['width'], $sizes['height']);
                break;
        }

        $original->cropImage($sizes['width'], $sizes['height'], $offset['x'], $offset['y']);

        return $original;
    }

    /**
     * He gets the coordinates of the center of the shift.
     * @param Imagick $image
     * @param string $width
     * @param string $height
     * @return array
     * @access private
     * @final
     */
    final private function getOffsetCenter(Imagick $image, $width, $height)
    {
        $size = $image->getImageGeometry();

        return array(
            'x' => (int) (($size['width'] - $width) / 2),
            'y' => (int) (($size['height'] - $height)/2)
        );
    }

    /**
     * Gets a balanced shift coordinates.
     * @param Imagick $image
     * @param string $width
     * @param string $height
     * @return array
     * @access private
     * @final
     */
    final private function getOffsetBalanced(Imagick $image, $width, $height)
    {
        $size = $image->getImageGeometry();

        $points = array();
        $halfWidth = ceil($size['width'] / 2);
        $halfHeight = ceil($size['height'] / 2);

        $clone = clone($image);
        $clone->cropimage($halfWidth, $halfHeight, 0, 0);

        $cloneSize = $clone->getImageGeometry();
        $tmpFile = sys_get_temp_dir() . '/image' . rand();
        $clone->writeimage($tmpFile);
        $tmp = imagecreatefromjpeg($tmpFile);

        $xcenter = $ycenter = $sum = 0;

        $tmpSize = round($cloneSize['height'] * $cloneSize['width']) / 50;

        for ($k=0; $k < $tmpSize; $k++) {
            $i = mt_rand(0, $cloneSize['width'] - 1);
            $j = mt_rand(0, $cloneSize['height'] - 1);

            $rgb = imagecolorat($tmp, $i, $j);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $val = ($r * 0.299) + ($g * 0.587) + ($b * 0.114);
            $sum += $val;
            $xcenter += ($i + 1) * $val;
            $ycenter += ($j + 1) * $val;
        }

        if ($sum > 0) {
            $xcenter /= $sum;
            $ycenter /= $sum;
        }

        $points[] = array(
            'x' => $xcenter,
            'y' => $ycenter,
            'sum' => $sum / round($cloneSize['height'] * $cloneSize['width'])
        );

        $totalWeight = array_reduce(
            $points,
            function ($result, $array) {
                return $result + $array['sum'];
            }
        );

        $xcenter = $ycenter = 0;

        $totalPoints = count($points);
        for ($idx = 0; $idx < $totalPoints; $idx++) {
            $xcenter += $points[$idx]['x'] * ($points[$idx]['sum'] / $totalWeight);
            $ycenter += $points[$idx]['y'] * ($points[$idx]['sum'] / $totalWeight);
        }

        $topleftX = max(0, ($xcenter - $width / 2));
        $topleftY = max(0, ($ycenter - $height / 2));

        if ($topleftX + $width > $size['width']) {
            $topleftX -= ($topleftX + $width) - $size['width'];
        }

        if ($topleftY + $height > $size['height']) {
            $topleftY -= ($topleftY + $height) - $size['height'];
        }

        return array('x' => $topleftX, 'y' => $topleftY);
    }

    /**
     * Gets a entropy shift coordinates.
     * @param Imagick $image
     * @param string $width
     * @param string $height
     * @return array
     * @access private
     * @final
     */
    final private function getOffsetEntropy(Imagick $image, $width, $height)
    {
        $clone = clone($image);
        $clone->edgeimage(1);
        $clone->modulateImage(100, 0, 100);
        $clone->blackThresholdImage('#070707');
        $clone->blurImage(3, 2);

        return array(
            'x' => $this->sliceEntropy($clone, $width, 'h'),
            'y' => $this->sliceEntropy($clone, $height, 'v')
        );
    }

    /**
     * Gets the offset point relative to the grid.
     * @param Imagick $image
     * @param string $size
     * @param string $axis
     * @return integer
     * @access private
     * @final
     */
    final private function sliceEntropy(Imagick $image, $size, $axis)
    {
        $rank = array();

        $imageSize = $image->getImageGeometry();
        $originalSize = $axis == 'h' ? $imageSize['width'] : $imageSize['height'];
        $longSize = $axis == 'h' ? $imageSize['height'] : $imageSize['width'];

        if ($originalSize == $targetSize) {
            return 0;
        }

        $number = 25;
        $sliceSize = ceil($originalSize / $number);

        $requiredSlices = ceil($size / $sliceSize);

        $start = 0;
        while ($start < $originalSize) {
            $slice = clone($image);
            switch ($axis) {
                case 'h':
                    $slice->cropImage($sliceSize, $longSize, $start, 0);
                    break;
                case 'v':
                    $slice->cropImage($longSize, $sliceSize, 0, $start);
                    break;
            }

            $histogram = $slice->getImageHistogram();
            $area = $slice->getImageGeometry();

            $value = 0.0;

            $colors = count($histogram);
            for ($idx = 0; $idx < $colors; $idx++) {
                $p = $histogram[$idx]->getColorCount() / $area['height'] * $area['width'];
                $value = $value + $p * log($p, 2);
            }

            $rank[] = array('offset' => $start, 'entropy' => -$value);
            $start += $sliceSize;
        }

        $max = $maxIndex = 0;
        for ($i = 0; $i < $number - $requiredSlices; $i++) {
            $temp = 0;
            for ($j = 0; $j < $requiredSlices; $j++) {
                $temp += $rank[$i + $j]['entropy'];
            }

            if ($temp > $max) {
                $maxIndex = $i;
                $max = $temp;
            }
        }

        return $rank[$maxIndex]['offset'];
    }
}
