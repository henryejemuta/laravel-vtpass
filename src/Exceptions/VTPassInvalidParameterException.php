<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: VTPassInvalidParameterException.php
 * Date Created: 7/14/20
 * Time Created: 5:28 PM
 */

namespace HenryEjemuta\LaravelVTPass\Exceptions;


use Throwable;

class VTPassInvalidParameterException extends \Exception
{
    /**
     * VTPassFailedRequestException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code = -1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
