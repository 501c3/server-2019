<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 12:40 PM
 */

namespace App\Common;

use Symfony\Component\Yaml\Yaml;

class YamlModel
{
    const
       VALUE_KEYS = ['abbr','note','domain'];


    private $model;
    private $domain;
    private $competition;


    /**
     * @param string $file
     * @return array|string
     */
    public function declareModels(string $file)
    {
        $this->model =  Yaml::parseFile($file);
        return $this->model;
    }

    /**
     * @param string $file
     * @return array|string
     */
    public function declareCompetitions(string $file)
    {

        $this->competition = Yaml::parseFile($file);
        return $this->competition;
    }

    public function declareDomains(string $file)
    {
        $domains = Yaml::parseFile($file);
        foreach($domains as $domain)
        {
            $this->domain[$domain] = [];
        }
        return $this->domain;
    }


    /**
     * @param string $file
     * @throws AppException
     * @throws \Exception
     */
    public function declareValues(string $file)
    {
        $str = file_get_contents($file);
        $domainValuePositionArray = YamlPosition::yamlAddPosition($str);
        $domainPositionArray = array_keys($domainValuePositionArray);
        $validKeys = array_keys($this->domain);
        foreach($domainPositionArray as $domainPosition) {
            list($domain,$position) = explode('|', $domainPosition);
            if(!in_array($domain, $validKeys))
            {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                        $domain,$position,$validKeys);
            }
        }
        foreach($domainValuePositionArray as $domainPosition => $valuesPositions) {
           $array = explode('|',$domainPosition);
           list($domainKey)=$array;
           foreach($valuesPositions as $valuePosition=>$descriptorPosition){
               list($valueKey) = explode('|',$valuePosition);
               $descriptor = $this->valueDescriptor($descriptorPosition);
               $this->domain[$domainKey][$valueKey]=$descriptor;
           }
        }
    }

    /**
     * @param $descriptorIn
     * @return array
     * @throws AppException
     */

    private function valueDescriptor($descriptorIn) {
        $descriptorOut = [];
        foreach($descriptorIn as $keyPosition=>$valuePosition) {
            list($key,$keyPos) = explode('|', $keyPosition);
            list($value) = explode('|', $valuePosition);
            if(!in_array($key, self::VALUE_KEYS)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                        $key, $keyPos, self::VALUE_KEYS);
            }
            $descriptorOut[$key] = $value;
        }
        return $descriptorOut;
    }

    public function fetchDomains()
    {
        return array_keys($this->domain);
    }

    /**
     * @param string|null $domain
     * @return mixed
     * @throws AppException
     */
    public function fetchValues(string $domain=null)
    {
        if(is_null($domain)) {
            return $this->domain;
        }
        if(!isset($this->domain[$domain])) {
            throw new AppException(AppExceptionCodes::INVALID_PARAMETER,
                                    $domain,
                                    null,
                                    array_keys($this->domain));
        }
        return $this->domain[$domain];
    }

    public function getStructure()
    {
        return $this->model;
    }

    /**
     * @return array|string
     * @throws \Exception
     */
    public function pullData()
    {
        return YamlPosition::isolate($this->model, YamlPosition::STRING);
    }

    /**
     * @return array|string
     * @throws \Exception
     */
    public function pullPositions()
    {
        return YamlPosition::isolate($this->model, YamlPosition::POSITION);
    }
}