[![Latest Stable Version](https://poser.pugx.org/dmamontov/favicon/v/stable.svg)](https://packagist.org/packages/dmamontov/favicon)
[![License](https://poser.pugx.org/dmamontov/favicon/license.svg)](https://packagist.org/packages/dmamontov/favicon)
[![Total Downloads](https://poser.pugx.org/dmamontov/favicon/downloads)](https://packagist.org/packages/dmamontov/favicon)
[![PHP Classes](https://img.shields.io/badge/php-classes-blue.svg)](http://www.phpclasses.org/package/9265-PHP-Create-Favicon-images-for-sites-and-mobile-devices.html)

Favicon Generator
=================

This class can create Favicon images for sites and mobile devices.

It takes a give base icon image and creates multiple versions of the image for use as favicon on Web sites or be displayed by mobile devices like those using systems of Apple, Microsoft, and Android.

The class can generate all the versions of the icon images with the different sizes, as well the necessary HTML to reference the icon images in a Web page.

The margins, color, compression, crop method and screen orientation are configurable parameters.

## Requirements
* PHP version ~5.3.3
* Module installed Imagick

## Installation

1) Install [composer](https://getcomposer.org/download/)

2) Follow in the project folder:
```bash
composer require dmamontov/favicon ~1.0.0
```

In config `composer.json` your project will be added to the library `dmamontov/favicon`, who settled in the folder `vendor/`. In the absence of a config file or folder with vendors they will be created.

If before your project is not used `composer`, connect the startup file vendors. To do this, enter the code in the project:
```php
require 'path/to/vendor/autoload.php';
```

### Example of work
```php
$fav = new FaviconGenerator(__DIR__ . '/tests.png');

$fav->setCompression(FaviconGenerator::COMPRESSION_VERYHIGH);

$fav->setConfig(array(
    'apple-background'    => FaviconGenerator::COLOR_BLUE,
    'apple-margin'        => 15,
    'android-background'  => FaviconGenerator::COLOR_GREEN,
    'android-margin'      => 15,
    'android-name'        => 'My app',
    'android-url'         => 'http://slobel.ru',
    'android-orientation' => FaviconGenerator::ANDROID_PORTRAIT,
    'ms-background'       => FaviconGenerator::COLOR_GREEN,
));

echo $fav->createAllAndGetHtml();
```
