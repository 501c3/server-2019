<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 12:40 PM
 */
namespace App\Common;

use App\Entity\Models\Domain;
use App\Entity\Models\Event;
use App\Entity\Models\Model;
use App\Entity\Models\Person;
use App\Entity\Models\Subevent;
use App\Entity\Models\Team;
use App\Entity\Models\Value;
use App\Repository\Models\DomainRepository;
use App\Repository\Models\EventRepository;
use App\Repository\Models\ModelRepository;
use App\Repository\Models\PersonRepository;
use App\Repository\Models\SubeventRepository;
use App\Repository\Models\TeamRepository;
use App\Repository\Models\ValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class YamlDbModel
{
    const
        VALUE_KEYS = ['abbr', 'note', 'domain','label'],
        PLAYER_DOMAINS = ['type', 'status', 'sex', 'age', 'proficiency'],
        EVENT_DOMAINS = ['type','status','sex','genre','style','substyle','proficiency','age','tag','dance'];


    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /** @var array */
    protected $competition;

    /** @var array */
    protected $model = [] ;

    /** @var array */
    protected $domain;

    /** @var array  */
    private $domainObject = [];

    /** @var array */
    protected $value;


    /**
     * @var array
     *
     * $person[<type>][<status>][<sex>][<years>][<proficiency>]=<Person>
     *
     * <type>:=Amateur|Professional
     * <status>:=Teacher|Student
     * <sex>:=Male|Female
     * <years>:= int
     * <proficiency>:= string
     */
    protected $person;
    /**
     * @var array
     *
     * $team[<type>][status][sex][<age>][<proficiency>]=<team>
     * <team> := dbObject containing <record>
     * <record> = ['type'=><value>, 'status'=><status>, 'sex'=><sex>,
     *             'age'=><age>, 'proficiency'=><proficiency>]
     * <type>:= string e.g.Amateur|Professional|Professional-Amateur
     * <sex>:= string e.g. Male|Female|Male-Male|Male-Female|Female-Female|Mixed-Sex
     *
     */
    protected $team;

    /**
     * @var array
     *
     * $event[<type>][<status>][<sex>][<model>][<age>][<proficiency>][<style>] = <event-list>
     * <event-list>:=[event-0,event-1...event-n]
     * <event-n>:=dbObject containing <record>
     * <substyle>:=string e.g. Latin, Smooth
     * <record>:=[type=><type>, status=><status>, sex=><sex>, model=><model>, age=><age> proficiency=><proficiency>,
     *            style=><style>, tag=><tag>, dances=>[substyle1=><dance-list>...substy=><dance-list>]
     *
     */

    protected $event;

    /**
     * @param string $file
     * @return array|string
     */

    protected $file;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function declareModels(string $file)
    {
        /** @var ModelRepository $repository */
        $repository=$this->entityManager->getRepository(Model::class);
        $names =  yaml_parse_file($file);
        foreach($names as $name){
            $model=$repository->create($name);
            $this->model[$name] = $model;
        }
        return $this->model;
    }

    /*
     * @param string $file
     * @return array|string
     */
//    public function declareCompetitions(string $file)
//    {
//
//        $this->competition = Yaml::parseFile($file);
//        return $this->competition;
//    }

    /**
     * @param string $file
     * @return mixed
     */
    public function declareDomains(string $file)
    {
        /** @var DomainRepository $repository */
        $repository=$this->entityManager->getRepository(Domain::class);
        $names = yaml_parse_file($file);
        foreach ($names as $idx=>$name) {
           $this->domainObject[$name]=$repository->create($name,$idx+1);
            $this->domain[$name] = [];
        }
        return $this->domain;
    }


    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws \Exception
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
                list($valueKey,$valuePosition) = explode('|', $valuePosition);
                $descriptor = $this->valueDescriptor($descriptorPosition);
                /** @var ValueRepository $repository */
                $repository=$this->entityManager->getRepository(Value::class);
                $domainObject = $this->domainObject[$domainKey];
                $abbr=$this->fetchAbbr($descriptor,$valueKey,$valuePosition);
                $valueObject = $repository->create($valueKey,$abbr,$domainObject);
                $this->domain[$domainKey][$valueKey] = $valueObject;
            }
        }
        return $this->domain;
    }


    /**
     * @param array $descriptor
     * @param string $valueKey
     * @param string $valuePosition
     * @return string
     * @throws AppException
     */
    private function fetchAbbr(array $descriptor, string $valueKey, string $valuePosition) : string
    {

        if(isset($descriptor['domain'])) {
            /** @var Value $object */
           $object=$this->domain[$descriptor['domain']][$valueKey];
           return $object->getAbbr();
        } elseif (isset($descriptor['abbr'])) {
            return $descriptor['abbr'];
        } else {
            throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $valueKey, $valuePosition);
        }
    }


    /*
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
//    protected function declare(string $file,
//                               string $methodFor,
//                               array $domains,
//                               string $check,
//                               string $build,
//                               string $return) : array
//    {
//        $this->file = $file;
//        $modelsPositions = YamlPosition::yamlAddPosition($file);
//        foreach ($modelsPositions as $modelPos => $records) {
//            list($model, $position) = explode('|', $modelPos);
//            if (!in_array($model, $this->model)) {
//                throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE, $this->file, $model, $position);
//            }
//            $this->$methodFor($records, $domains, $check, $build, $model);
//        }
//        return $this->$return;
//    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws \Exception
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
     * @param $class
     * @return array
     * @throws AppException
     */
    public function fetchQuickSearch($class)
    {
        /** @var PersonRepository $repository */
        $repository=$this->entityManager->getRepository($class);
        switch($class) {
            case Model::class:
                $this->model = $repository->fetchQuickSearch();
                return $this->model;
            case Domain::class:
                $this->domainObject = $repository->fetchQuickSearch();
                return $this->domainObject;
            case Value::class:
                $this->domain=$repository->fetchQuickSearch();
                return $this->domain;
            case Person::class:
                $this->person=$repository->fetchQuickSearch();
                return $this->person;
            case Team::class:
                $this->team=$repository->fetchQuickSearch();
                return $this->team;
            case Event::class:
                $this->event=$repository->fetchQuickSearch();
                return $this->event;
        }
        $row = 'R'.__LINE__.'C0';
        throw new AppException(AppExceptionCodes::UNHANDLED_CONDITION, __FILE__,$row);
    }


    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws \Exception
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
     * @throws \Exception
     */
    public function declareEvents(string $file) : array
    {
        $this->file = $file;
        $modelsPositions = YamlPosition::yamlAddPosition($file);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, array_keys($this->model))) {
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
     * @throws \Exception
     */
    public function declareEventValues(string $file) : array
    {
        $this->file = $file;
        $modelsPositions = YamlPosition::yamlAddPosition($file);
        foreach ($modelsPositions as $modelPos => $records) {
            list($model, $position) = explode('|', $modelPos);
            if (!in_array($model, array_keys($this->model))) {
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
     * @throws \Exception
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
     * @throws AppException
     * @throws \Exception
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
        $row = 'R'.__LINE__.'C0';
        throw new AppException(AppExceptionCodes::UNHANDLED_CONDITION, __FILE__,$row);
    }

    /**
     * @param string $key
     * @param $dataPosition
     * @return array|string
     * @throws AppException
     * @throws \Exception
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
        $row = 'R'.__LINE__.'C0';
        throw new AppException(AppExceptionCodes::UNHANDLED_CONDITION, __FILE__,$row);
    }


    /**
     * @param string $model
     * @param string $key
     * @param $dataPosition
     * @return array|null|string
     * @throws AppException
     * @throws \Exception
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
        $row = 'R'.__LINE__.'C0';
        return new AppException(AppExceptionCodes::UNHANDLED_CONDITION, __FILE__,$row);
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
     * @throws \Exception
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

    /*
     * @param $cache
     */

    protected function personsBuild($cache)
    {
        /** @var PersonRepository $repository */
        $repository = $this->entityManager->getRepository(Person::class);
        $type = $cache['type'];
        $status = $cache['status'];
        $typeValue = $this->domain['type'][$type];
        $statusValue = $this->domain['status'][$status];
        if (!isset($this->person[$type])) {
            $this->person[$type] = [];
        }
        if (!isset($this->person[$type][$status])) {
            $this->person[$type][$status] = [];
        }
        $descriptionL0 = ['type' => $type, 'status' => $status];
        $sexPtr = &$this->person[$type][$status];
        foreach ($cache['sex'] as $sex) {
            $sexValue = $this->domain['sex'][$sex];
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
                    $proficiencyValue = $this->domain['proficiency'][$proficiency];
                    $descriptionL3 = $descriptionL2;
                    $descriptionL3['proficiency'] = $proficiency;
                    if (!isset($sexPtr[$sex][$years][$proficiency])) {
                        $collection = [$typeValue,$statusValue,$sexValue,$proficiencyValue];
                        $person=$repository->create($descriptionL3,$years,$collection);
                        $sexPtr[$sex][$years][$proficiency] = $person;
                    }
                }
            }
        }
    }


    /**
     * @param $cache
     */
    protected function teamsBuild($cache)
    {
        /** @var TeamRepository $repository */
        $repository = $this->entityManager->getRepository(Team::class);
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
                        $team=$repository->create($descriptionL3);
                        $sexPtr[$sex][$age][$proficiency] = $team;
                    }
                }
            }
        }
    }

    /**
     * @param $values
     * @param string $model
     */
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
               if(!isset($this->value[$model][$domain][$value])) {
                    $valObj = $this->domain[$domain][$value];
                    $this->value[$model][$domain][$value]=$valObj;
               }
           }
       }
    }

    /**
     * @param array $cache
     * @param string $model
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
                          $styleDancesTag, $descriptionL3, $model);
                }
            }
        }
    }

    /**
     * @param $prfPtr
     * @param array $styleDancesTag
     * @param array $description
     * @param string $model
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function buildEventSpecifics(& $prfPtr, array $styleDancesTag, array $description, string $model)
    {
        /** @var SubeventRepository $repositorySubevent */
        $repositorySubevent = $this->entityManager->getRepository(Subevent::class);
        /** @var EventRepository $repositoryEvent */
        $repositoryEvent = $this->entityManager->getRepository(Event::class);
        /** @var ValueRepository $repositoryValue */
        $repositoryValue = $this->entityManager->getRepository(Value::class);
        $searchValue=$repositoryValue->fetchQuickSearch();
        $modelObject = $this->model[$model];
        foreach($styleDancesTag as $record){
            $descriptionL1 = $description;
            $descriptionL1['tag'] = $record['tag'];
            $tag = $record['tag'];
            $tagObject = $searchValue['tag'][$tag];
            foreach($record['style'] as $style=>$rec) {
                if(!isset($prfPtr[$style])) {
                    $prfPtr[$style] = [];
                }
                $styleObject = $searchValue['style'][$style];
                $values = new ArrayCollection();
                $values->add($styleObject);
                $values->add($tagObject);
                $descriptionL2 = $descriptionL1;
                $descriptionL2['style']=$style;
                switch($rec['disposition']){
                    case 'multiple-events':
                        foreach($rec['substyle'] as $substyle=>$danceCollections){
                            $descriptionL3 = $descriptionL2;
                            $descriptionL3['dances']=[];
                            foreach($danceCollections as $dances) {
                                $descriptionL4=$descriptionL3;
                                $descriptionL4['dances'][$substyle]=$dances;
                                /** @var Event $event */
                                $event = $repositoryEvent->create($descriptionL4, $modelObject, $values);
                                $repositorySubevent->create($descriptionL4,$event);
                                array_push($prfPtr[$style],$event);
                            }
                        }
                        break;
                    case 'single-event':
                        $descriptionL3 = $descriptionL2;
                        $descriptionL3['dances']=[];
                        $subeventDescriptions = [];
                        foreach($rec['substyle'] as $substyle=>$dances) {
                            $descriptionL4 = $descriptionL3;
                            $descriptionL4['dances'][$substyle]=$dances;
                            $descriptionL3['dances'][$substyle]=$dances;
                            array_push($subeventDescriptions,$descriptionL4);
                        }
                        /** @var Event $event */
                        $event=$repositoryEvent->create($descriptionL3,$modelObject,$values);
                        foreach($subeventDescriptions as $description) {
                            $repositorySubevent->create($description,$event);
                        }
                        array_push($prfPtr[$style],$event);
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

    /**
     * @return array
     */
    public function fetchDomainStrings()
    {
        return array_keys($this->domain);
    }


    /**
     * @param string|null $domain
     * @return mixed
     * @throws AppException
     */
    public function fetchValueStrings(string $domain=null)
    {
        if(is_null($domain)) {
            return $this->domain;
        }
        if(!isset($this->domain[$domain])) {
            throw new AppException(AppExceptionCodes::INVALID_PARAMETER,
                $this->file, $domain,null,array_keys($this->domain));
        }
        return array_keys($this->domain[$domain]);
    }

    /**
     * @return array
     */
    public function fetchModels() : array
    {
        return $this->model;
    }

}