<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 12:40 PM
 */

namespace App\Common;

use Exception;
use Symfony\Component\Yaml\Yaml;

class YamlModel
{
    const
        VALUE_KEYS = ['abbr', 'note', 'domain'],
        PERSON_DOMAINS = ['type', 'status', 'sex', 'age', 'proficiency'];


    private $model;
    private $domain;
    private $competition;

    /**
     * @var array
     *
     * $person[<type>][<status>][<sex>][<model>][<years>][<proficiency>]=<record>
     */
    private $person;
    /**
     * @var array
     *
     * $team[<type>][<grouping>][<status>][<sex>][<model>][<age>][<proficiency>]=<record>
     * <record> = ['type'=><value>, 'grouping'=><value>, 'status'=><value>, 'sex'=><value>,
     *              'model'=><model>, 'age'=><value>, 'proficiency'=><value>
     *               'people'=>[person_records], 'events'=>['event_list']
     */
   // private $team;

    /**
     * @var array
     * $event[<type>][<grouping>][<status>][<sex>][<model>][<age>][<proficiency>][<tag>]=<record>
     *
     */
   // private $event;

    /**
     * @param string $file
     * @return array|string
     */
    public function declareModels(string $file)
    {
        $this->model = Yaml::parseFile($file);
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

    /**
     * @param string $file
     * @return mixed
     */
    public function declareDomains(string $file)
    {
        $domains = Yaml::parseFile($file);
        foreach ($domains as $domain) {
            $this->domain[$domain] = [];
        }
        return $this->domain;
    }


    /**
     * @param string $file
     * @throws AppException
     * @throws Exception
     */
    public function declareValues(string $file)
    {
        $str = file_get_contents($file);
        $domainValuePositionArray = YamlPosition::yamlAddPosition($str);
        $domainPositionArray = array_keys($domainValuePositionArray);
        $validKeys = array_keys($this->domain);
        foreach ($domainPositionArray as $domainPosition) {
            list($domain, $position) = explode('|', $domainPosition);
            if (!in_array($domain, $validKeys)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                    $domain, $position, $validKeys);
            }
        }
        foreach ($domainValuePositionArray as $domainPosition => $valuesPositions) {
            $array = explode('|', $domainPosition);
            list($domainKey) = $array;
            foreach ($valuesPositions as $valuePosition => $descriptorPosition) {
                list($valueKey) = explode('|', $valuePosition);
                $descriptor = $this->valueDescriptor($descriptorPosition);
                $this->domain[$domainKey][$valueKey] = $descriptor;
            }
        }
    }

    /**
     * @param string $file
     * @throws AppException
     * @throws Exception
     */
    public function declarePersons(string $file)
    {
        $str = file_get_contents($file);
        $modelsPositions = YamlPosition::yamlAddPosition($str);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, $this->model)) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $model, $position);
            }
            $this->personsFor($model, $records);
        }
    }

    /**
     * @param string $model
     * @param array $records
     * @throws AppException
     * @throws Exception
     */
    private function personsFor(string $model, array $records)
    {
        foreach ($records as $record) {
            $keysPositions = array_keys($record);
            foreach ($keysPositions as $keyPos) {
                list($key, $position) = explode('|', $keyPos);
                if (!in_array($key, self::PERSON_DOMAINS)) {
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                        $key, $position, self::PERSON_DOMAINS);
                }
            }
            $keysFound = YamlPosition::isolate($keysPositions, YamlPosition::STRING);
            $keysPositions = YamlPosition::isolate($keysPositions, YamlPosition::POSITION);
            $difference = array_diff(self::PERSON_DOMAINS, $keysFound);
            if (count($difference)) {
                $found = join(',', $difference);
                throw new AppException(AppExceptionCodes::MISSING_KEYS, $found, null, $keysPositions);
            }

            $this->personsCheckThenBuild($model, $record);
        }
    }


    /**
     * @param string $model
     * @param array $record
     * @throws Exception
     */
    private function personsCheckThenBuild(string $model, array $record)
    {
        $cache = [];
        foreach ($record as $keyPosition => $dataPosition) {
            list($key) = explode('|', $keyPosition);
            $cache[$key] = $this->personsCheck($key,$dataPosition);
        }
        $this->personsBuild($model, $cache);
    }

    /**
     * @param string $key
     * @param $dataPosition
     * @return array|string
     * @throws Exception
     */
    private function personsCheck(string $key, $dataPosition)
    {
        switch ($key) {
            case 'type':
            case 'status':
                list($value, $pos) = explode('|', $dataPosition);
                if (!isset($this->domain[$key][$value])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $value, $pos);
                }
                return YamlPosition::isolate($dataPosition);
            case 'sex':
            case 'proficiency':
                foreach ($dataPosition as $valuePosition) {
                    list($value, $pos) = explode('|', $valuePosition);
                    if (!isset($this->domain[$key][$value])) {
                        throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $value, $pos);
                    }
                }
                return YamlPosition::isolate($dataPosition);
            case 'age':
                return $this->personCheckAge($dataPosition);
        }
        return null;
    }

    /**
     * @param array $dataPosition
     * @return array
     * @throws AppException
     * @throws Exception
     */
    private function personCheckAge(array $dataPosition) : array
    {
        /** @var array $allYears */
        $allYears = [];
        $yearAge = [];
        foreach ($dataPosition as $keyPosition => $valuePosition) {
            list($yearRange, $rangePos) = explode('|', $keyPosition);
            if (preg_match('/(?P<lb>\d+)\-(?P<ub>\d+)/', $yearRange, $matches)) {
                if ($matches['lb'] > $matches['ub']) {
                    throw new AppException(AppExceptionCodes::INVALID_RANGE, $yearRange, $rangePos);
                }
                $more = range($matches['lb'], $matches['ub']);
                $overlap = array_intersect($allYears, $more);
                $allYears = array_merge($allYears, $more);
                if (count($overlap)) {
                    throw new AppException(AppExceptionCodes::OVERLAPPING_RANGE, $yearRange, $rangePos);
                }
                list($age, $agePos) = explode('|', $valuePosition);
                if(!isset($this->domain['age'][$age])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $age, $agePos);
                }
                foreach($more as $year) {
                    $yearAge[$year]=$age;
                }
            } else {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $yearRange, $rangePos);
            }
        }
        $min = min($allYears);
        $max = max($allYears);
        $every = range($min, $max);
        $missing = array_diff($every, $allYears);
        $keysPositions = array_keys($dataPosition);
        $positions = YamlPosition::isolate($keysPositions, YamlPosition::POSITION);
        if (count($missing)) {
            throw new AppException(AppExceptionCodes::MISSING_KEYS,
                join(',', $missing), null, $positions);
        }
        return $yearAge;

    }

    /**
     * @param string $model
     * @param $cache
     * @throws Exception
     */

    private function personsBuild(string $model, $cache)
    {
       if(!isset($this->person[$cache['type']])) {
           $this->person[$cache['type']]=[];
       }
       if(!isset($this->person[$cache['type']][$cache['status']])) {
           $this->person[$cache['type']][$cache['status']] = [];
       }
       $description = ['type'=>$cache['type'], 'status'=>$cache['status']];
       $this->personsBuildAtSex($model,$this->person[$cache['type']][$cache['status']], $cache, $description);
    }

    /**
     * @param string $model
     * @param array $sexPtr
     * @param array $cache
     * @param array $description
     */
    private function personsBuildAtSex(string $model, array &$sexPtr, array $cache, array $description)
    {
        foreach($cache['sex'] as $sex) {
            $nextDescription = $description;
            if(!isset($sexPtr[$sex])) {
                $sexPtr[$sex] = [];
                $nextDescription['sex']=$sex;
            }
            if(!isset($sexPtr[$sex][$model])) {
                $sexPtr[$sex][$model]=[];
                $nextDescription['model']=$model;
            }
            $this->personsBuildAtModel($sexPtr[$sex][$model], $cache, $nextDescription );
        }

    }

    /**
     * @param $modelPtr
     * @param array $cache
     * @param array $description
     */
    private function personsBuildAtModel(&$modelPtr, array $cache, array $description)
    {
       foreach($cache['age'] as $years => $age) {
           $nextDescription = $description;
           if(!isset($modelPtr[$years])) {
               $modelPtr[$years]=[];
           }
           $nextDescription['years']=$years;
           $nextDescription['age']=$age;
           foreach($cache['proficiency'] as $proficiency) {
               $subsequentDescription = $nextDescription;
               $subsequentDescription['proficiency'] = $proficiency;
               if(!isset($modelPtr[$years][$proficiency])) {
                   $modelPtr[$years][$proficiency]= $subsequentDescription;
               }
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
     * @throws Exception
     */
    public function pullData()
    {
        return YamlPosition::isolate($this->model, YamlPosition::STRING);
    }

    /**
     * @return array|string
     * @throws Exception
     */
    public function pullPositions()
    {
        return YamlPosition::isolate($this->model, YamlPosition::POSITION);
    }
}