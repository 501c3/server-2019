<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/28/19
 * Time: 5:05 PM
 */

namespace App\DataTransformer;


use Throwable;

class RegistrationException extends \Exception
{
    const MISSING_FIELDS= 8110;

    public function __construct(array $fields, int $code, Throwable $previous = null)
    {
        parent::__construct("Field Errors",$code,$previous);
    }

}