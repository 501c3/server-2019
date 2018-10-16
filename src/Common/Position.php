<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/13/18
 * Time: 8:37 PM
 */

namespace App\Common;


class Position
{
    public static function strToPos(string $string) {
        $pos=[];
        $result = preg_match('/R(?P<row>\d+)C(?P<col>\d+)/',$string, $pos);
        return $pos;
    }
}