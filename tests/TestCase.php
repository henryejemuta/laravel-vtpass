<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: TestCase.php
 * Date Created: 7/13/20
 * Time Created: 6:52 PM
 */

namespace HenryEjemuta\LaravelVTPass\Tests;


use HenryEjemuta\LaravelVTPass\LaravelVTPassServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelVTPassServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

}
