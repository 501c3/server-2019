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
        PLAYER_DOMAINS = ['type', 'status', 'sex', 'age', 'proficiency'];


    private $model;
    private $domain;
    private $competition;

    /**
     * @var array
     *
     * $person[<type>][<status>][<sex>][<model>][<years>][<proficiency>]=<record>
     *
     * <type>:=Amateur|Professional
     * <status>:=Teacher|Student
     * <sex>:=Male|Female
     * <years>:= number
     */
    private $person;
    /**
     * @var array
     *
     * $team[<type>][<sex>][<model>][<age>][<proficiency>]=<record>
     * <record> = ['type'=><value>, 'status'=><value>, 'sex'=><value>,
     *              'model'=><model>, 'age'=><value>, 'proficiency'=><value>
     *               'people'=>[person_records], 'events'=>['event_list']
     * <type>:=Amateur|Professional
     * <sex>:=Male|Femal|Male-Male|Male-Female|Female-Female
     *
     */
    private $team;

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

    private $file;

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
        $this->file = $file;
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
        $this->file = $file;
        $str = file_get_contents($file);
        $domainValuePositionArray = YamlPosition::yamlAddPosition($str);
        $domainPositionArray = array_keys($domainValuePositionArray);
        $validKeys = array_keys($this->domain);
        foreach ($domainPositionArray as $domainPosition) {
            list($domain, $position) = explode('|', $domainPosition);
            if (!in_array($domain, $validKeys)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
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
        return $this->domain;
    }


    /**
     * @param string $file
     * @param string $methodFor
     * @param array $domains
     * @param string $check
     * @param string $build
     * @return array
     * @throws AppException
     * @throws Exception
     */
    protected function declare(string $file, string $methodFor, array $domains, string $check, string $build) : array
    {
        $this->file = $file;
        $str = file_get_contents($file);
        $modelsPositions = YamlPosition::yamlAddPosition($str);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, $this->model)) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $model, $position);
            }
            $this->$methodFor($model, $records, $domains, $check, $build);
        }
        return $this->person;
    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declarePersons(string $file) : array
    {
        $this->declare($file,
            'entitiesFor',
            self::PLAYER_DOMAINS,
            'personsCheck',
            'personsBuild');
        return $this->person;
    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declareTeams(string $file) : array
    {
        $this->declare($file,
            'entitiesFor',
            self::PLAYER_DOMAINS,
            'teamsCheck',
            'teamsBuild');
        return $this->team;
    }

    /**
     * @param string $model
     * @param array $records
     * @param array $domains
     * @param string $checkFn
     * @param string $buildFn
     * @throws AppException
     * @throws Exception
     */
    protected function entitiesFor(string $model, array $records, array $domains, string $checkFn, string $buildFn)
    {
        foreach ($records as $record) {
            $keysPositions = array_keys($record);
            foreach ($keysPositions as $keyPos) {
                list($key, $position) = explode('|', $keyPos);
                if (!in_array($key, $domains)) {
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                        $key, $position, $domains);
                }
            }
            $keysFound = YamlPosition::isolate($keysPositions, YamlPosition::STRING);
            $keysPositions = YamlPosition::isolate($keysPositions, YamlPosition::POSITION);
            $difference = array_diff($domains, $keysFound);
            if (count($difference)) {
                $found = join(',', $difference);
                throw new AppException(AppExceptionCodes::MISSING_KEYS, $this->file,
                    $found, null, $keysPositions);
            }

            $cache = [];
            foreach ($record as $keyPosition => $dataPosition) {
                list($key) = explode('|', $keyPosition);
                $cache[$key] = $this->$checkFn($key,$dataPosition);
            }
            $this->$buildFn($model, $cache);
        }
    }


    /**
     * @param string $key
     * @param $dataPosition
     * @return array|string
     * @throws Exception
     */
    protected function personsCheck(string $key, $dataPosition)
    {
        switch ($key) {
            case 'age':
                return $this->personCheckAge($dataPosition);
            case 'proficiency':
            case 'sex':
                if(!is_array($dataPosition)) {
                    list($value, $pos) = explode('|', $dataPosition);
                    throw new AppException(AppExceptionCodes::ARRAY_EXPECTED, $this->file, $value, $pos);
                }
                foreach ($dataPosition as $valuePosition) {
                    list($value, $pos) = explode('|', $valuePosition);
                    if (!isset($this->domain[$key][$value])) {
                        throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                            $value, $pos);
                    }
                }
                return YamlPosition::isolate($dataPosition);
            case 'type':
            case 'status':
                list($value, $pos) = explode('|', $dataPosition);
                if (!isset($this->domain[$key][$value])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $value, $pos);
                }
                return YamlPosition::isolate($dataPosition);

        }
        return null;
    }

    /**
     * @param string $key
     * @param $dataPosition
     * @return array|null|string
     * @throws AppException
     * @throws Exception
     */
    protected function teamsCheck(string $key, $dataPosition)
    {
        switch ($key) {
            case 'status':
            case 'type':
                list($value, $pos) = explode('|', $dataPosition);
                if (!isset($this->domain[$key][$value])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $value, $pos);
                }
                return YamlPosition::isolate($dataPosition);
            case 'age':
            case 'proficiency':
            case 'sex':
                if(!is_array($dataPosition)) {
                    list($value, $pos) = explode('|', $dataPosition);
                    throw new AppException(AppExceptionCodes::ARRAY_EXPECTED, $this->file, $value, $pos);
                }
                foreach ($dataPosition as $valuePosition) {
                    list($value, $pos) = explode('|', $valuePosition);
                    if (!isset($this->domain[$key][$value])) {
                        throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                            $value, $pos);
                    }
                }
                return YamlPosition::isolate($dataPosition);
        }
        return null;

    }

    /**
     * @param string $key
     * @param $dataPosition
     * @return array|null|string
     * @throws AppException
     * @throws Exception
     */
    protected function eventsCheck(string $key, $dataPosition)
    {
        switch ($key) {
            case 'type':
            case 'status':
                list($value, $pos) = explode('|', $dataPosition);
                if (!isset($this->domain[$key][$value])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $value, $pos);
                }
                return YamlPosition::isolate($dataPosition);
            case 'sex':
            case 'proficiency':
            case 'age':
                if(!is_array($dataPosition)) {
                    list($value, $pos) = explode('|', $dataPosition);
                    throw new AppException(AppExceptionCodes::ARRAY_EXPECTED, $this->file, $value, $pos);
                }
                foreach ($dataPosition as $valuePosition) {
                    list($value, $pos) = explode('|', $valuePosition);
                    if (!isset($this->domain[$key][$value])) {
                        throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                            $value, $pos);
                    }
                }
                return YamlPosition::isolate($dataPosition);
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
                    throw new AppException(AppExceptionCodes::INVALID_RANGE, $this->file,
                        $yearRange, $rangePos);
                }
                $more = range($matches['lb'], $matches['ub']);
                $overlap = array_intersect($allYears, $more);
                $allYears = array_merge($allYears, $more);
                if (count($overlap)) {
                    throw new AppException(AppExceptionCodes::OVERLAPPING_RANGE, $this->file,
                        $yearRange, $rangePos);
                }
                list($age, $agePos) = explode('|', $valuePosition);
                if(!isset($this->domain['age'][$age])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                        $age, $agePos);
                }
                foreach($more as $year) {
                    $yearAge[$year]=$age;
                }
            } else {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                    $yearRange, $rangePos);
            }
        }
        $min = min($allYears);
        $max = max($allYears);
        $every = range($min, $max);
        $missing = array_diff($every, $allYears);
        $keysPositions = array_keys($dataPosition);
        $positions = YamlPosition::isolate($keysPositions, YamlPosition::POSITION);
        if (count($missing)) {
            throw new AppException(AppExceptionCodes::MISSING_KEYS, $this->file,
                join(',', $missing), null, $positions);
        }
        return $yearAge;

    }

    /**
     * @param string $model
     * @param $cache
     * @throws Exception
     */

    protected function personsBuild(string $model, $cache)
    {
        if (!isset($this->person[$cache['type']])) {
            $this->person[$cache['type']] = [];
        }
        if (!isset($this->person[$cache['type']][$cache['status']])) {
            $this->person[$cache['type']][$cache['status']] = [];
        }
        $descriptionL0 = ['type' => $cache['type'], 'status' => $cache['status']];
        $sexPtr = &$this->person[$cache['type']][$cache['status']];
        foreach ($cache['sex'] as $sex) {
            $descriptionL1 = $descriptionL0;
            if (!isset($sexPtr[$sex])) {
                $sexPtr[$sex] = [];
            }
            $descriptionL1['sex'] = $sex;
            if (!isset($sexPtr[$sex][$model])) {
                $sexPtr[$sex][$model] = [];
            }
            $descriptionL1['model'] = $model;
            foreach ($cache['age'] as $years => $age) {
                $descriptionL2 = $descriptionL1;
                if (!isset($modelPtr[$years])) {
                    $sexPtr[$sex][$model][$years] = [];
                }
                $descriptionL2['years'] = $years;
                $descriptionL2['age'] = $age;
                foreach ($cache['proficiency'] as $proficiency) {
                    $descriptionL3 = $descriptionL2;
                    $descriptionL3['proficiency'] = $proficiency;
                    if (!isset($sexPtr[$sex][$model][$years][$proficiency])) {
                        $sexPtr[$sex][$model][$years][$proficiency] = $descriptionL3;
                    }
                }
            }
        }
    }


    protected function teamsBuild(string $model, $cache)
    {
        if (!isset($this->team[$cache['type']])) {
            $this->team[$cache['type']] = [];
        }
        if (!isset($this->team[$cache['type']][$cache['status']])) {
            $this->team[$cache['type']][$cache['status']] = [];
        }
        $descriptionL0 = ['type' => $cache['type'], 'status' => $cache['status']];
        $sexPtr = &$this->team[$cache['type']][$cache['status']];
        foreach ($cache['sex'] as $sex) {
            $descriptionL1 = $descriptionL0;
            if (!isset($sexPtr[$sex])) {
                $sexPtr[$sex] = [];
            }
            $descriptionL1['sex'] = $sex;
            if (!isset($sexPtr[$sex][$model])) {
                $sexPtr[$sex][$model] = [];
            }
            $descriptionL1['model'] = $model;

            foreach ($cache['age'] as  $age) {
                $descriptionL2 = $descriptionL1;
                if (!isset($modelPtr[$age])) {
                    $sexPtr[$sex][$model][$age] = [];
                }
                $descriptionL2['age'] = $age;
                foreach ($cache['proficiency'] as $proficiency) {
                    $descriptionL3 = $descriptionL2;
                    $descriptionL3['proficiency'] = $proficiency;
                    if (!isset($sexPtr[$sex][$model][$age][$proficiency])) {
                        $sexPtr[$sex][$model][$age][$proficiency] = $descriptionL3;
                    }
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
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
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
            throw new AppException(AppExceptionCodes::INVALID_PARAMETER, $this->file,
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

}