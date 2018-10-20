<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/13/18
 * Time: 8:34 PM
 */

namespace App\Common;


class AppExceptionCodes
{
    const
    INVALID_POSITION = 0010,
    UNHANDLED_MESSAGE = 0020,
    NOT_IN_COLLECTION = 1000,
    INVALID_PARAMETER = 1010,
    PARAMETER_MISSING = 9999;

    public static $messages =
        [self::INVALID_POSITION => 'Invalid Position',
         self::NOT_IN_COLLECTION => 'Not in collection',
         self::INVALID_PARAMETER => 'Invalid parameter',
         self::UNHANDLED_MESSAGE => 'Unhandled message'];


    /**
     * @param string $string
     * @return array
     * @throws \Exception
     */
    public static function strToPos(string $string) {
        $pos=[];
        $result = preg_match('/R(?P<row>\d+)C(?P<col>\d+)/',$string, $pos);
        if(!$result) {
          $message = sprintf('"%s" passed to exception.  Expected string of form "R\d+C\d+" where \d in [0-9]',$string);
          throw new \Exception($message, self::INVALID_POSITION);
        }
        return $pos;
    }

    /**
     * Example
     * $found = 'SomeString'
     * $position = 'RXXCXX' where RXXCXX is a (row,column) position e.g. R3C22 == row:3, column=22
     *
     * @param int $code
     * @param string|null $found
     * @param string|null $position
     * @param array|null $expected
     * @return string
     * @throws \Exception
     */

    public static function getMessage(int $code,
                                      string $found = null,
                                      string $position = null,
                                      array $expected = null)
    {
        switch($code) {
            case self::NOT_IN_COLLECTION:
                return self::notInCollectionMessage($found,$position,$expected);
            case self::INVALID_PARAMETER:
                return self::invalidParameterMessage($found,$expected);

        }
        throw new \Exception('Unhandled message',self::UNHANDLED_MESSAGE);
    }

    /**
     * @param string $found
     * @param string $position
     * @param array|null $expected
     * @return string
     * @throws \Exception
     */
    private static function notInCollectionMessage(string $found=null,
                                                   string $position=null,
                                                   array $expected = null) : string
    {
        if (!$found) {
            throw new \Exception("Bad parameter: \$found", self::PARAMETER_MISSING);
        }
        if (!$position) {
            throw new \Exception("Bad parameter: \$position", self::PARAMETER_MISSING);
        }
        $pos = self::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d)", $pos['row'],$pos['col']);
        $message.= $expected?'. Expected ['.join(', ', $expected).'].':'';
        return $message;
    }

    private static function invalidParameterMessage(string $found, $expected) : string
    {
        $message = sprintf("Found '$found'");
        $message.= $expected?'. Expected ['.join(', ', $expected).'].':'';
        return $message;
    }
}