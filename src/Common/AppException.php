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

    const
        MESSAGE_FPE = "Found '%s' at (row:%d,col:%d) but expected %s in file: %s. Reference: %d",
        MESSAGE_FP  ="'%s' as (row:%d,col:%d) is an unrecognized value in file: %s. Reference: %d",
        MESSAGE_MK  ="Missing %s between lines %d-%d in file: %s. Reference: %d",
        MESSAGE_UC  ="Unhandled condition in source file: %s. Code: %d";


    /**
     * AppException constructor.
     * @param int $code
     * @param $parameters
     * @param Throwable|null $exception
     * @throws AppException
     */
    public function __construct(int $code,$parameters,Throwable $exception=null)
    {
        $message = $this->message($code, ...$parameters);
        parent::__construct($message, $code, $exception);
    }/** @noinspection SpellCheckingInspection */


    /**
     * @param string $string
     * @return array
     * @throws AppException
     */
    public static function strToPos(string $string) {
        $pos=[];
        $result = preg_match('/R(?P<row>\d+)C(?P<col>\d+)/',$string, $pos);
        if(!$result) {
            throw new AppException(AppExceptionCodes::UNHANDLED_CONDITION, [__FILE__]);
        }
        return $pos;
    }


    /**
     * @param $code
     * @param null $pathfile
     * @param null $found
     * @param null $position
     * @param null $expected
     * @return string
     * @throws AppException
     */
    private function message($code,$pathfile=null,$found=null,$position=null,$expected=null)
    {
        $parts = pathinfo($pathfile);
        $file = $parts['filename'].'.'.$parts['extension'];
        switch($code) {
            case AppExceptionCodes::FOUND_BUT_EXPECTED:
                return self::messageFPE($code, $file, $found, $position, $expected);
            case AppExceptionCodes::UNRECOGNIZED_VALUE:
                return self::messageFP($code, $file, $found, $position);
            case AppExceptionCodes::MISSING_KEYS:
                return self::messageMK($code,$file,$found,$position);
            default:
                return self::messageUC($code,$file);
        }
    }


    /**
     * @param $code
     * @param $file
     * @param $found
     * @param $position
     * @param $expected
     * @return string
     * @throws AppException
     */
    public static function messageFPE($code,$file,$found,$position,$expected)
    {
        $str=is_array($expected)?'['.join("|",$expected).']':$expected;
        $pos = self::strToPos($position);
        return sprintf(self::MESSAGE_FPE,$found,$pos['row'],$pos['col'],$str,$file,$code);
    }

    /**
     * @param $code
     * @param $file
     * @param $found
     * @param $position
     * @return string
     * @throws AppException
     */
    public static function messageFP($code,$file,$found,$position)
    {
        $pos = self::strToPos($position);
        return sprintf(self::MESSAGE_FP,$found,$pos['row'],$pos['col'],$file,$code);
    }


    /**
     * @param $code
     * @param $file
     * @param $missingKeys
     * @param $positions
     * @return string
     * @throws AppException
     */
    public static function messageMK($code,$file,$missingKeys,$positions)
    {
        $lines = [];
        foreach($positions as $position) {
            $pos=self::strToPos($position);
            $lines[] = $pos['row'];
        }
        $max = max($lines);
        $min = min($lines);
        return sprintf(self::MESSAGE_MK,'['.join('|',$missingKeys).']',$min,$max,$file,$code);
    }

    public static function messageUC($code,$file)
    {
        return sprintf(self::MESSAGE_UC, $file, $code);
    }

}