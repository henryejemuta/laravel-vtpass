# Laravel VTPass

[![Build Status](https://travis-ci.org/henryejemuta/laravel-vtpass.svg?branch=master)](https://travis-ci.org/henryejemuta/laravel-vtpass)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/henryejemuta/laravel-vtpass.svg?style=flat-square)](https://packagist.org/packages/henryejemuta/laravel-vtpass)
[![Latest Stable Version](https://poser.pugx.org/henryejemuta/laravel-vtpass/v/stable)](https://packagist.org/packages/henryejemuta/laravel-vtpass)
[![Total Downloads](https://poser.pugx.org/henryejemuta/laravel-vtpass/downloads)](https://packagist.org/packages/henryejemuta/laravel-vtpass)
[![License](https://poser.pugx.org/henryejemuta/laravel-vtpass/license)](https://packagist.org/packages/henryejemuta/laravel-vtpass)
[![Quality Score](https://img.shields.io/scrutinizer/g/henryejemuta/laravel-vtpass.svg?style=flat-square)](https://scrutinizer-ci.com/g/henryejemuta/laravel-vtpass)

A laravel package to seamlessly integrate VTPass api within your laravel application

## What is VTPass
VTPass is a platform through which you can make convenient payment for your day to day services like Phone Airtime Recharge, Internet Data bundle subscription, Cable TV subscription such as DTSV, GOTV, Startimes, Electricity bills (PHCN) and many other services.

Create a VTPass Account [Sign Up](https://bit.ly/3kFIfns).

Look up VTPass API Documentation [API Documentation](https://www.vtpass.com/documentation/introduction/).


## Non-Laravel Usage

If you are using this package in a non-laravel project, or you need a framework agnostic usage of this package, using the vanilla php version [php-vtpass-vtu](https://github.com/henryejemuta/php-vtpass-vtu) is suggested.

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
> To use the VTPass package you must import the VTPass Facades with the import statement below; Other Classes import is based on your specific usage and would be highlighted in their corresponding sections.
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
