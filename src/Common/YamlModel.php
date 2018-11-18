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
        VALUE_KEYS = ['abbr', 'note', 'domain','label'],
        PLAYER_DOMAINS = ['type', 'status', 'sex', 'age', 'proficiency'],
        EVENT_DOMAINS = ['type','status','sex','genre','style','substyle','proficiency','age','tag','dance'];


    /**
     * @var array
     */
    protected $model;
    /**
     * @var array
     * $this->domain[<domain>][<value>]=[abbr=><abbr>]
     */
    protected $domain;

    /**
     * @var array
     * $this->value[<model>][<domain>]=[<value list>]
     */
    protected $value;

    private $competition;

    /**
     * @var array
     *
     * $person[<type>][<status>][<sex>][<years>][<proficiency>]=<record>
     *
     * <type>:=Amateur|Professional
     * <status>:=Teacher|Student
     * <sex>:=Male|Female
     * <years>:= number
     */
    protected $person;
    /**
     * @var array
     *
     * $team[<type>][status][sex][<age>][<proficiency>]=<record>
     * <record> = ['type'=><value>, 'status'=><value>, 'sex'=><value>,
                     'age'=><value>, 'proficiency'=><value>
     *               'people'=>[person_records], 'events'=>['event_list']
     * <type>:=Amateur|Professional
     * <sex>:=Male|Femal|Male-Male|Male-Female|Female-Female
     *
     */
    protected $team;

    /**
     * @var array
     * $event[<type>][<status>][<sex>][<model>][<age>][<proficiency>][<style>]=<record>
     * <record> := [type=> <value> 'status'=><value>, 'sex'=><value>
     *              'model'=> <model>, 'age'=> <value>, 'proficiency'=><value>
     *              'style'=> <value>, 'events' => <event-list>]
     *
     * <event-list> := [<event-record_0>,..<event-record_n>]
     * <event-record-n> := ['substyle' => [dance-list], 'tag' => <value> ]
     */

    protected $event;

    /**
     * @param string $file
     * @return array|string
     */

    protected $file;

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
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declareValues(string $file)
    {
        $this->file = $file;
        $domainValuePositionArray = YamlPosition::yamlAddPosition($file);
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
     * @param string $return
     * @return array
     * @throws AppException
     * @throws Exception
     */
    protected function declare(string $file,
                               string $methodFor,
                               array $domains,
                               string $check,
                               string $build,
                               string $return) : array
    {
        $this->file = $file;
        $modelsPositions = YamlPosition::yamlAddPosition($file);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, $this->model)) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $model, $position);
            }
            $this->$methodFor($records, $domains, $check, $build, $model);
        }
        return $this->$return;
    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declarePersons(string $file) : array
    {
        $this->file = $file;
        $records = YamlPosition::yamlAddPosition($file);
        $this->entitiesFor($records,
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
        $this->file = $file;
        $records = YamlPosition::yamlAddPosition($file);
        $this->entitiesFor($records,
            self::PLAYER_DOMAINS,
            'teamsCheck',
            'teamsBuild');
        return $this->team;
    }


    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declareEvents(string $file) : array
    {
        $this->file = $file;
        $modelsPositions = YamlPosition::yamlAddPosition($file);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, $this->model)) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                    $this->file, $model, $position);
            }
            $this->entitiesFor($records,
                self::PLAYER_DOMAINS,
                'eventsCheck',
                'eventsBuild',
                $model);
        }
        return $this->event;
    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws Exception
     */
    public function declareEventValues(string $file) : array
    {
        $this->file = $file;
        $modelsPositions = YamlPosition::yamlAddPosition($file);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, $this->model)) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $model, $position);
            }
            $this->entitiesFor($records, self::EVENT_DOMAINS, 'valuesCheck', 'valuesBuild', $model);
        }
        return $this->value;
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
    protected function entitiesFor(array $records, array $domains, string $checkFn, string $buildFn, string $model=null)
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
                switch($checkFn) {
                    case 'valuesCheck':
                    case 'personsCheck':
                    case 'teamsCheck':
                        $cache[$key] = $this->$checkFn($key,$dataPosition);
                        break;
                    case 'eventsCheck':
                        $cache[$key] = $this->$checkFn($key,$dataPosition,$model);

                }
            }
            if(is_null($model)){
                $this->$buildFn($cache);
            } else {
                $this->$buildFn($cache,$model);
            }

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
        // TODO: Throw exception when this is reached.
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
        //TODO: Throw exception here.
        return null;
    }


    /**
     * @param string $model
     * @param string $key
     * @param $dataPosition
     * @return array|null|string
     * @throws AppException
     * @throws Exception
     */
    protected function eventsCheck(string $key, $dataPosition,string $model)
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
            case 'proficiency':
               $this->eventDanceCheck($dataPosition,$model);
               return YamlPosition::isolate($dataPosition);

        }
        return null;
    }

    /**
     * @param string $model
     * @param array $dataPosition
     * @throws AppException
     */
    private function eventDanceCheck(array $dataPosition,string $model)
    {
        foreach($dataPosition as $proficiencyPos=>$styleDances) {
            list($proficiency, $position) = explode('|',$proficiencyPos);
            if(!isset($this->domain['proficiency'][$proficiency])) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                    $this->file,$proficiency,$position);
            }
            foreach($styleDances as $section) {
                foreach($section as $keyPos=>$subsectionPos) {
                    list($key,$pos) = explode('|',$keyPos);
                    if(!in_array($key,['tag','style'])) {
                        throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                            $this->file,$key,$pos,['tag','style']);
                    }
                    switch($key){
                        case 'tag':
                            list($tag,$tagPos) = explode('|',$subsectionPos);
                            if(!isset($this->domain['tag'][$tag])){
                                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                                    $this->file,$tag,$tagPos);
                            }
                            if(!isset($this->value[$model]['tag'][$tag])){
                                $collection=array_keys($this->value[$model]['tag']);
                                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                    $this->file,$tag,$tagPos,$collection);
                            }
                            break;
                        case 'style':
                            $this->eventDanceSubstyleCheck($subsectionPos,$model);
                    }
                }
            }
        }
    }


    /**
     * @param string $model
     * @param array $subsectionPos
     * @throws AppException
     */
    private function eventDanceSubstyleCheck(array $subsectionPos,string $model)
    {
        foreach($subsectionPos as $stylePosition=>$eventsPositions){
            list($style,$stylePos) = explode('|',$stylePosition);
            if(!isset($this->domain['style'][$style])) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                    $this->file,$style,$stylePos);
            }
            if(!isset($this->value[$model]['style'][$style])){
                $collection=array_keys($this->value[$model]['style']);
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                    $this->file,$style,$stylePos,$collection);
            }
            $tmp = [];
            $collection=['disposition','substyle'];
            foreach($eventsPositions as $keyPos=>$dataPositions){
                list($key,$pos) = explode('|',$keyPos);
                if(!in_array($key,$collection)) {
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                        $this->file,$key,$pos,$collection);
                }
                switch($key){
                    case 'disposition':
                        $dispositionCollection = ['multiple-events','single-event'];
                        list($disposition,$dispositionPos) = explode('|',$dataPositions);
                        if(!in_array($disposition,$dispositionCollection)){
                            throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                $this->file,$disposition,$dispositionPos,$dispositionCollection);
                        }
                        $tmp['disposition']=$disposition;
                        break;
                    case 'substyle':
                        if(!isset($tmp['substyle'])) {
                            $tmp['substyle']=[];
                        }
                        foreach($dataPositions as $substylePos=>$eventsDancesPos) {
                            list($substyle,$position) = explode('|',$substylePos);
                            if(!isset($this->domain['substyle'][$substyle])) {
                                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                                    $this->file,$substyle,$position);
                            }
                            $collection = array_keys($this->value[$model]['substyle']);
                            if(!isset($this->value[$model]['substyle'][$substyle])) {
                                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                    $this->file,$substyle,$position,$collection);
                            }
                            $tmp['substyle'][$substyle]=$eventsDancesPos;
                        }
                }

            }
            switch($tmp['disposition']) {
                case 'multiple-events':
                    foreach($tmp['substyle'] as $substyle=>$dancesPositions) {
                        foreach ($dancesPositions as $collectionPositions) {
                            if (is_scalar($collectionPositions)) {
                                list($scaler, $scalerPos) = explode('|', $collectionPositions);
                                throw new AppException(AppExceptionCodes::ARRAY_EXPECTED,
                                    $this->file, $scaler, $scalerPos);
                            }
                            foreach ($collectionPositions as $dancePosition) {
                                list($dance, $dancePos) = explode('|', $dancePosition);
                                if (!isset($this->domain['dance'][$dance])) {
                                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                                        $this->file, $dance, $dancePos);
                                }
                                if (!isset($this->value[$model]['dance'][$dance])) {
                                    $danceCollection = array_keys($this->value[$model]['dance']);
                                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                        $this->file, $dance, $dancePos, $danceCollection);
                                }
                            }
                        }
                    }
                   break;
                case 'single-event':
                    foreach($tmp['substyle'] as $substsyle=>$collectionPositions){
                        if(is_array($collectionPositions[0])) {
                            list($scaler,$scalerPos) = explode('|',$collectionPositions[0][0]);
                            throw new AppException(AppExceptionCodes::SCALER_EXPECTED,
                                $this->file,$scaler,$scalerPos);
                        }
                        foreach($collectionPositions as $dancePos) {
                            list($dance,$dancePos) = explode('|', $dancePos);
                            if(!isset($this->domain['dance'][$dance])) {
                                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                                    $this->file,$dance,$dancePos);
                            }
                            if(!isset($this->value[$model]['dance'][$dance])) {
                                $danceCollection = array_keys($this->value[$model]['dance']);
                                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                    $this->file,$dance,$dancePos,$danceCollection);
                            }
                        }
                    }
            }
        }
    }

    /**
     * @param string $dataPosition
     * @return array
     * @throws AppException
     */
    private function personCheckAge(string $dataPosition) : array
    {
        /** @var array $allYears */
        list($yearRange, $rangePos) = explode('|', $dataPosition);
        if (preg_match('/(?P<lb>\d+)\-(?P<ub>\d+)/', $yearRange, $matches)) {
            if ($matches['lb'] > $matches['ub']) {
                throw new AppException(AppExceptionCodes::INVALID_RANGE, $this->file,
                    $yearRange, $rangePos);
            }
        } else {
            throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file,
                $yearRange, $rangePos);
        }
        $years = range($matches['lb'], $matches['ub']);
        return $years;
    }

    /**
     * @param string $key
     * @param $valuesPositions
     * @return array|string
     * @throws AppException
     * @throws Exception
     */
    protected function valuesCheck(string $key, $valuesPositions)
    {
        foreach($valuesPositions as $valuePos) {
            list($value,$position)=explode('|',$valuePos);
            if(!isset($this->domain[$key][$value])) {
                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                                    $this->file,
                                     $value,
                                     $position);
            }
        }
        return YamlPosition::isolate($valuesPositions);
    }

    /**
     * @param $cache
     */

    protected function personsBuild($cache)
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
            $descriptionL1['sex']=$sex;
            foreach ($cache['age'] as $years) {
                $descriptionL2 = $descriptionL1;
                if (!isset($modelPtr[$years])) {
                    $sexPtr[$sex][$years] = [];
                }
                $descriptionL2['years'] = $years;
                foreach ($cache['proficiency'] as $proficiency) {
                    $descriptionL3 = $descriptionL2;
                    $descriptionL3['proficiency'] = $proficiency;
                    if (!isset($sexPtr[$sex][$years][$proficiency])) {
                        $sexPtr[$sex][$years][$proficiency] = $descriptionL3;
                    }
                }
            }
        }
    }


    protected function teamsBuild($cache)
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
            foreach ($cache['age'] as  $age) {
                $descriptionL2 = $descriptionL1;
                if (!isset($modelPtr[$age])) {
                    $sexPtr[$sex][$age] = [];
                }
                $descriptionL2['age'] = $age;
                foreach ($cache['proficiency'] as $proficiency) {
                    $descriptionL3 = $descriptionL2;
                    $descriptionL3['proficiency'] = $proficiency;
                    if (!isset($sexPtr[$sex][$age][$proficiency])) {
                        $sexPtr[$sex][$age][$proficiency] = $descriptionL3;
                    }
                }
            }
        }
    }

    protected function valuesBuild($values,string $model)
    {
       if(!isset($this->value[$model])) {
           $this->value[$model]=[];
       }
       foreach($values as $domain=>$valueList) {
           if (!isset($this->value[$model][$domain])) {
               $this->value[$model][$domain] = [];
           }
           foreach($valueList as $value){
               if(!isset($this->value[$model][$domain][$value])){
                   $this->value[$model][$domain][$value]=[];
               }
           }
       }
    }

    /**
     * @param string $model
     * @param $cache
     */
    protected function eventsBuild(array $cache,string $model)
    {
        if (!isset($this->event[$cache['type']])) {
            $this->event[$cache['type']] = [];
        }
        if (!isset($this->event[$cache['type']][$cache['status']])) {
            $this->event[$cache['type']][$cache['status']] = [];
        }
        $descriptionL0 = ['type' => $cache['type'], 'status' => $cache['status']];
        $sexPtr = & $this->event[$cache['type']][$cache['status']];
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
                if (!isset($sexPtr[$sex][$model][$age])) {
                    $sexPtr[$sex][$model][$age] = [];
                }
                $descriptionL2['age'] = $age;
                foreach ($cache['proficiency'] as $proficiency=>$styleDancesTag) {
                    $descriptionL3 = $descriptionL2;
                    if(!isset($sexPtr[$sex][$model][$age][$proficiency])){
                        $sexPtr[$sex][$model][$age][$proficiency]=[];
                    }
                    $descriptionL3['proficiency'] = $proficiency;
                    $this->buildEventSpecifics(
                        $sexPtr[$sex][$model][$age][$proficiency],
                          $styleDancesTag, $descriptionL3);
                }
            }
        }
    }

    private function buildEventSpecifics(& $prfPtr, array $styleDancesTag, array $description)
    {
        foreach($styleDancesTag as $record){
            $descriptionL1 = $description;
            $descriptionL1['tag'] = $record['tag'];
            foreach($record['style'] as $style=>$rec) {
                if(!isset($prfPtr[$style])) {
                    $prfPtr[$style] = ['events'=>[]];
                }
                $descriptionL2 = $descriptionL1;
                $descriptionL2['style']=$style;
                switch($rec['disposition']){
                    case 'multiple-events':
                        foreach($rec['substyle'] as $substyle=>$danceCollections){
                            $descriptionL3 = $descriptionL2;
                            $substyleDances=[];
                            foreach($danceCollections as $dances) {
                                $substyleDances[$substyle]=$dances;
                                $descriptionL4=$descriptionL3;
                                $descriptionL4['dances']=$substyleDances;
                                $prfPtr[$style]['events'][]=$descriptionL4;
                            }
                        }
                        break;
                    case 'single-event':
                        $descriptionL2['dances']=$rec['substyle'];
                        $prfPtr[$style]['events'][]=$descriptionL2;
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
            throw new AppException(AppExceptionCodes::INVALID_PARAMETER,
                $this->file, $domain,null,array_keys($this->domain));
        }
        return $this->domain[$domain];
    }

    public function fetchModels()
    {
        return $this->model;
    }
}