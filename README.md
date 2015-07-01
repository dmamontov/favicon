[![Latest Stable Version](https://poser.pugx.org/dmamontov/favicon/v/stable.svg)](https://packagist.org/packages/dmamontov/favicon)
[![License](https://poser.pugx.org/dmamontov/favicon/license.svg)](https://packagist.org/packages/dmamontov/favicon)

Favicon Generator
=================

Class generation favicon for browsers and devices Android, Apple, Windows and display of html code. It supports a large number of settings such as margins, color, compression, three different methods of crop and screen orientation.

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
