<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/5/18
 * Time: 8:16 AM
 */

namespace App\Common;


use Throwable;

class AppBuildException extends \Exception
{

  public function __construct(int $code = 0,$parameters,Throwable $previous = null)
  {
      $message =  $this->message(...$parameters);
      parent::__construct($message, $code, $previous);
  }

  private function message(string $pathfile,int $line, string $arrName,array $index,\DateTime $dateTime)
  {
      $parts = pathinfo($pathfile);
      $file = $parts['filename'].'.'.$parts['extension'];
      $variable = '$this->'.$arrName."['".join("']['",$index)."']";
      $timeStr = $dateTime->format(\DateTimeInterface::ATOM);
      return "$variable could not be found in file:$file at line:$line.  Time: $timeStr";
  }
}