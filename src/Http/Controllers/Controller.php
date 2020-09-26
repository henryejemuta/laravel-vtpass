<?php
/**
 * Created By: Henry Ejemuta
 * PC: Enrico Systems
 * Project: laravel-vtpass
 * Company: Stimolive Technologies Limited
 * Class Name: Controller.php
 * Date Created: 9/26/20
 * Time Created: 7:06 PM
 */

namespace HenryEjemuta\LaravelVTPass\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
