<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: VTPass.php
 * Date Created: 7/13/20
 * Time Created: 8:44 PM
 */

namespace HenryEjemuta\LaravelVTPass\Facades;

use Illuminate\Support\Facades\Facade;

class VTPass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vtpass';
    }
}
