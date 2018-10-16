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
    PARAMETER_MISSING = 9999,
    NOT_IN_COLLECTION = 1000;

    public static $messages =
        [self::NOT_IN_COLLECTION=>''];

    public static function getMessage(int $code,
                                      string $found = null,
                                      string $position = null,
                                      array $expected = null)
    {
        switch($code) {
            case self::NOT_IN_COLLECTION:
                return self::notInCollectionMessage($found,$position,$expected);
        }
    }

    /**
     * @param string $found
     * @param string $position
     * @param array|null $expected
     * @return string
     * @throws \Exception
     */
    private static function notInCollectionMessage(string $found=null, string $position=null, array $expected = null) : string
    {
        if (!$found) {
            throw new \Exception("Missing exception parameter: \$found", self::PARAMETER_MISSING);
        }
        if (!$position) {
            throw new \Exception("Missing exception parameter: \$position", self::PARAMETER_MISSING);
        }
        $pos = Position::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d)", $pos['row'],$pos['col']);
        $message.= $expected?'. Expected ['.join(', ', $expected).'].':'';
        return $message;
    }
}