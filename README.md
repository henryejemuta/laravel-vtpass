# Laravel VTPass

[![Build Status](https://travis-ci.org/orobogenius/sansdaemon.svg?branch=master)](https://travis-ci.org/orobogenius/sansdaemon)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/henryejemuta/laravel-vTPass.svg?style=flat-square)](https://packagist.org/packages/henryejemuta/laravel-vTPass)
[![Latest Stable Version](https://poser.pugx.org/henryejemuta/laravel-vTPass/v/stable)](https://packagist.org/packages/henryejemuta/laravel-vTPass)
[![Total Downloads](https://poser.pugx.org/henryejemuta/laravel-vTPass/downloads)](https://packagist.org/packages/henryejemuta/laravel-vTPass)
[![License](https://poser.pugx.org/henryejemuta/laravel-vTPass/license)](https://packagist.org/packages/henryejemuta/laravel-vTPass)
[![Quality Score](https://img.shields.io/scrutinizer/g/henryejemuta/laravel-vTPass.svg?style=flat-square)](https://scrutinizer-ci.com/g/henryejemuta/laravel-vTPass)

A laravel package to seamlessly integrate vTPass api within your laravel application

## What is VTPass
VTPass is a platform through which you can make convenient payment for your day to day services like Phone Airtime Recharge, Internet Data bundle subscription, Cable TV subscription such as DTSV, GOTV, Startimes, Electricity bills (PHCN) and many other services.

Create a VTPass Account [Sign Up](https://bit.ly/3kFIfns).

Look up VTPass API Documentation [API Documentation](https://www.vtpass.com/documentation/introduction/).

## Installation

You can install the package via composer:

```bash
composer require henryejemuta/laravel-vtpass
```

Publish VTPass configuration file, migrations as well as set default details in .env file:

```bash
php artisan vtpass:init
```


## Usage
> To use the vTPass package you must import the VTPass Facades with the import statement below; Other Classes import is based on your specific usage and would be highlighted in their corresponding sections.
>
``` php
...
use HenryEjemuta\LaravelVTPass\Facades\VTPass;
...

```

### VTPass Facades Overview
``` php


   


```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Bugs & Issues

If you notice any bug or issues with this package kindly create and issues here [ISSUES](https://github.com/henryejemuta/laravel-vtpass/issues)

### Security

If you discover any security related issues, please email henry.ejemuta@gmail.com instead of using the issue tracker.

## Credits

- [Henry Ejemuta](https://github.com/henryejemuta)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
