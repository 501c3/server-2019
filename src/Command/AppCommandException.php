<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/19/18
 * Time: 6:22 PM
 */

namespace App\Command;


use Throwable;

class AppCommandException extends \Exception
{
   public function __construct( int $code = 0, $parameters, Throwable $previous = null)
   {
       $message = $this->message(...$parameters);
       parent::__construct($message, $code, $previous);
   }

   private function message($found,$expected=null) {
       $first = sprintf("Found %s",$found);
       if(is_null($expected)) {
           return $first.' which is invalid.';
       }
       $second = sprintf(" but expected %s.",$expected);
       return $first.$second;
   }
}