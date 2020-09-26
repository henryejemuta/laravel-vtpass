<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vtpass
 * Class Name: VTPassErrorException.php
 * Date Created: 7/14/20
 * Time Created: 5:14 PM
 */

namespace HenryEjemuta\LaravelVTPass\Exceptions;

class VTPassErrorException extends \Exception
{
    /**
     * VTPassErrorException constructor.
     * @param string $message
     * @param $code
     */
    public function __construct(string $message, $code)
    {
        parent::__construct($message, $code);
    }

}
