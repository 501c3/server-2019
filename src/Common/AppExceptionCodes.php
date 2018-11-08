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
    UNHANDLED_CONDITION=0030,
    NOT_IN_COLLECTION = 1000,
    INVALID_PARAMETER = 1010,
    UNRECOGNIZED_VALUE = 1020,
    MISSING_KEYS = 1030,
    INVALID_RANGE = 1040,
    OVERLAPPING_RANGE = 1050,
    ARRAY_EXPECTED = 1060,
    SCALER_EXPECTED = 1070,
    EMPTY_ARRAY_EXPECTED = 1080,
    PARTNER_VALUES = 1090,
    PARAMETER_MISSING = 9999;


    private static $file;

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
          throw new AppException(self::UNHANDLED_MESSAGE, self::$file, $message);
        }
        return $pos;
    }

    /**
     * Example
     * $found = 'SomeString'
     * $position = 'RXXCXX' where RXXCXX is a (row,column) position e.g. R3C22 == row:3, column=22
     *
     * @param int $code
     * @param string $file
     * @param string|null $found
     * @param string|null $position
     * @param array|null $expected
     * @return string
     * @throws \Exception
     */

    public static function getMessage(int $code,
                                      string $file,
                                      string $found = null,
                                      string $position = null,
                                      array $expected = null)
    {
        self::$file = $file;
        switch($code) {
            case self::NOT_IN_COLLECTION:
                return self::notInCollectionMessage($found,$position,$expected);
            case self::INVALID_PARAMETER:
                return self::invalidParameterMessage($found,$expected);
            case self::UNRECOGNIZED_VALUE:
                return self::unrecognizedValueMessage($found,$position);
            case self::INVALID_RANGE:
                return self::invalidRangeMessage($found,$position);
            case self::OVERLAPPING_RANGE:
                return self::overlappingRangeMessage($found,$position);
            case self::MISSING_KEYS:
                return self::missingKeysMessage($found, $expected);
            case self::EMPTY_ARRAY_EXPECTED:
                return self::emptyArrayMessage($found,$position);
            case self::ARRAY_EXPECTED:
                return self::arrayExpectedMessage($found,$position);
            case self::SCALER_EXPECTED:
                return self::scalerExpectedMessage($found,$position);
            case self::PARTNER_VALUES:
                return self::missingPartnerValueMessage($found,$position);
            case self::UNHANDLED_CONDITION:
                return self::unhandledConditionMessage($file,$found,$position);
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
        $message.= ' File: '.self::$file;
        return $message;
    }

    private static function invalidParameterMessage(string $found, array $expected) : string
    {
        $message = sprintf("Found '$found'");
        $message.= $expected?'. Expected ['.join(', ', $expected).'].':'';
        $message.= ' File: '.self::$file;
        return $message;
    }


    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function unrecognizedValueMessage(string $found, string $position) : string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d) which is not a recognized value.",
                            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;
    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function invalidRangeMessage(string $found, string $position) : string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d) which is not a valid range.",
            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;
    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function overlappingRangeMessage(string $found, string $position) : string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d) which is an overlapping range.",
            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;
    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function arrayExpectedMessage(string $found, string $position) : string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Found '$found' at (row:%d,col:%d) but expected an array ie dash '-'.",
            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;
    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */

    private static function scalerExpectedMessage(string $found, string $position) : string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Found array near $found (row:%d,col:%d) but expected scaler.",
            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;

    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function missingPartnerValueMessage(string $found, string $position): string
    {
        $pos = self::strToPos($position);
        $message = sprintf("Missing partner values near $found (row:%d,col:%d).",
                            $pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;

    }

    /**
     * @param string $missing
     * @param array $positions
     * @return string
     * @throws \Exception
     */
    private static function missingKeysMessage(string $missing, array $positions)
    {
        $lines = [];
        foreach($positions as $position) {
            $pos=self::strToPos($position);
            $lines[] = $pos['row'];
        }
        $max = max($lines);
        $min = min($lines);
        $message = sprintf("Missing %s between rows %s and %s. ",$missing,$min,$max);
        $message.= ' File: '.self::$file;
        return $message;
    }

    /**
     * @param string $found
     * @param string $position
     * @return string
     * @throws \Exception
     */
    private static function emptyArrayMessage(string $found, string $position)
    {
        /** @var array $pos */
        $pos = self::strToPos($position);
        $message = sprintf("Empty array expected near %s at (row:%d,col:%d). ",$found,$pos['row'],$pos['col']);
        $message.= ' File: '.self::$file;
        return $message;
    }

    private static function unhandledConditionMessage($file,$method,$line)
    {
        return "Unhandled condition in $file::$method at line:$line." ;
    }
}