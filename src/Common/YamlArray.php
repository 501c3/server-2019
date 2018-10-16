<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/26/18
 * Time: 5:32 PM
 */

namespace App\Common;
use Symfony\Component\Yaml\Yaml;

class YamlArray
{
    private static $positions;

    private static $lineCount;


    /**
     * @param string $string
     * @return array|string
     * @throws \Exception
     */
    public static function yamlToStringPosition(string $string)
    {
        $data=Yaml::parse($string);
        if(is_null($data)) {
            throw new \Exception("No yaml string to parse", 255);
        }
        $rowColumns=self::rowColumn($string);
        $positions=Yaml::parse($rowColumns);
        $stringPosition = self::stringPosition($data,$positions);
        return $stringPosition;
    }

    /**
     * @param string $string
     * @param array $collection
     * @return bool
     * @throws AppException
     */
    public static function isInCollection(string $string, array $collection)
    {
        if(strpos( $string, '|')) {
            list($str,$pos) = explode('|', $string);
            if (in_array($str,$collection)) {
                return true;
            }
            if ($pos) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,$str,$pos,$collection);
            }
        }
        if(in_array($string, $collection)) {
            return true;
        }
        return false;
    }

    /**
     * @param array $arr
     * @return bool
     */
    private static function isAssoc(array $arr)
    {
        if (is_array($arr)) {
            return array_keys($arr) !== range(0, count($arr) - 1);
        }
        return false;
    }


    /**
     * @param $mixed
     * @param bool $returnPosition
     * @return array
     * @throws \Exception
     */
    public static function isolateStringOrPosition($mixed, bool $returnPosition = false)
    {
        if(is_array($mixed)){
            if(self::isAssoc($mixed)){
                $result = [];
                foreach($mixed as $key=>$value) {
                    list($string,$position)=explode('|',$key);
                    $result[$returnPosition?$position:$string] = self::isolateStringOrPosition($value);
                }
                return $result;
            } else {
                $result = [];
                foreach($mixed as $value) {
                    $result[]=self::isolateStringOrPosition($value, $returnPosition);
                }
                return $result;
            }
        } elseif (is_string($mixed)) {
            list($string,$position) = explode('|',$mixed);
            return $returnPosition?$position:$string;
        }
        throw new \Exception('Error in parsing');
    }


    private static function stringPosition($data, $positions) {
        if(is_array($data) && is_array($positions)) {
            list($key,$keyPos,$dataPart,$dataPartPos) = self::current($data,$positions);
            if(self::isAssoc($data)) {
                $result = [];
                while($key) {
                    $recursiveResult = self::stringPosition($dataPart, $dataPartPos);
                    $result[$key.'|'.$keyPos] = $recursiveResult;
                    list($key, $keyPos, $dataPart, $dataPartPos) = self::next($data,$positions);
                }
                return $result;
            }else{
                $result = [];
                foreach($data as $i=>$dataPart) {
                    $result[]=self::stringPosition($dataPart,$positions[$i]);
                }
                return $result;
            }
        } elseif (is_string($data) && is_string($positions)) {
            return $data.'|'.$positions;
        }
        return null;
        // throw new \Exception('Error in parsing');
    }


    public static function getLineCount()
    {
        return self::$lineCount;
    }

    /**
     * @return mixed|null
     */
    public static function positionYaml()
    {
        return self::$positions;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function rowColumn(string $string)
    {
        /** @var array $lines */
        $lines=explode("\n",$string);
        self::$lineCount=count($lines);
        $row=0;
        $positions = [];
        foreach($lines as $line)
        {
            $row++;
            array_push($positions, self::position($row, $line));
        }
        self::$positions=join("\n",$positions);
        return self::$positions;
    }

    public static function position(int $row, $string)
    {
        $len=strlen($string);
        $col=0;
        $array=[];
        $match=[];
        $char=substr($string,$col,1);
        $rest=substr($string,$col);
        while($col<$len){
            if(in_array($char, [' ',',','{','}','[',']','-',':'])){
                $col++;
                array_push($array,$char);
            }elseif (preg_match('/[\<]*\w+([\s\-\.\@\<\>\+]*\w?)*/',$rest, $match)) {
                $size = strlen( $match[0] );
                $position = sprintf( 'R%dC%d',$row,$col+1);
                array_push( $array, $position );
                $col += $size;
            }else{
                $col++;
            }
            $char=substr($string,$col,1);
            $rest=substr($string,$col);
        }
        return join('',$array);
    }

    private static function current(&$data, &$position)
    {
        $dataValue=current($data);
        $dataPos=current($position);
        $keyValue=key($data);
        $keyPos=key($position);
        return [$keyValue,$keyPos,$dataValue,$dataPos];
    }

    private static function next(array &$data, array &$position) {
        $dataValue=next($data);
        $dataPos=next($position);
        $keyValue=key($data);
        $keyPos=key($position);
        return [$keyValue,$keyPos,$dataValue,$dataPos];
    }
}