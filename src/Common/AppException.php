<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/13/18
 * Time: 8:04 PM
 */

namespace App\Common;


use Throwable;

class AppException extends \Exception
{


    public function __construct(int $code = 0,
                                string $found=null,
                                string $position=null,
                                array $expected=null,
                                Throwable $previous = null)
    {
        $message = AppExceptionCodes::getMessage($code,$found,$position,$expected);
        parent::__construct($message, $code, $previous);
    }
}