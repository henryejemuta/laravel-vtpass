<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: InstallLaravelVTPassTest.php
 * Date Created: 7/13/20
 * Time Created: 7:34 PM
 */

namespace HenryEjemuta\LaravelVTPass\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use HenryEjemuta\LaravelVTPass\Tests\TestCase;

class InstallLaravelVTPassTest extends TestCase
{
    /** @test */
    function the_install_command_copies_a_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('vtpass.php'))) {
            unlink(config_path('vtpass.php'));
        }

        $this->assertFalse(File::exists(config_path('vtpass.php')));

        Artisan::call('vtpass:init');

        $this->assertTrue(File::exists(config_path('vtpass.php')));
    }
}
