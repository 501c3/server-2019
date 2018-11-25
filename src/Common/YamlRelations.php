<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/14/18
 * Time: 9:52 PM
 */

namespace App\Common;



use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class YamlRelations
 * @package App\Common
 */
class YamlRelations extends YamlModel
{

    const
        TOP_KEYS = ['competition','team-person','team-event'];

    /**
     * @var GeorgiaDanceSport
     */
    private $competition;
    /**
     * @var integer
     * Contains only the latest  teamPerson collections
     */

    private $fileNumber = 0;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * YamlRelations constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher = null)
    {
        $this->competition = new GeorgiaDanceSport();
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param array $relationsPositions
     * @throws AppException
     * @throws \Exception
     */
    private function checkTopKeys(array &$relationsPositions)
    {
        $topKeysPositions=array_keys($relationsPositions);
        $topKeys = YamlPosition::isolate($topKeysPositions);
        $diff=array_diff(self::TOP_KEYS, $topKeys);
        if(count($diff)) {
            $missing = join(',',$diff);
            $positions = YamlPosition::isolate($topKeysPositions,YamlPosition::POSITION);
            throw new AppException(AppExceptionCodes::MISSING_KEYS,$this->file,$missing,null,$positions);
        }
    }

    private function initializeTmpLocations(){
        foreach(['person','team','event','team-person','team-event'] as $tmp) {
            if(!file_exists("/tmp/gads/$tmp")){
                mkdir("/tmp/gads/$tmp",0777,true);
            }
            array_map('unlink', array_filter((array) glob("/tmp/gads/$tmp/*")));
        }
    }


    /**
     * @param string $file
     * @throws AppException
     * @throws \Exception
     */
    public function declareRelations(string $file)
    {
        $this->initializeTmpLocations();
        $this->file=$file;
        $relationsPositions =  YamlPosition::yamlAddPosition($file);
        foreach($relationsPositions as $keyPosition=>$recordList) {
            list($key,$position)=explode('|',$keyPosition);
            if(!in_array($key,self::TOP_KEYS)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                    $this->file, $key, $position,self::TOP_KEYS);
            }
        }
        $this->checkTopKeys($relationsPositions);
        foreach($relationsPositions as $keyPosition=>$recordList) {
            list($key,)=explode('|',$keyPosition);
            switch($key) {
                case 'competition':
                    list($competition) = explode('|',$recordList);
                    switch($competition){
                        case 'GeorgiaDanceSport':
                            $this->competition = new GeorgiaDanceSport();
                    }

                    break;
                case 'team-person':
                    foreach($recordList as $record) {
                        $this->relationsFor($record,self::PLAYER_DOMAINS,'teamPersonBuild');
                    }
                    break;
                case 'team-event':
                    foreach($recordList as $modelPosition=>$records) {
                        list($model,$position) = explode('|',$modelPosition);
                        if(!in_array($model,$this->model)) {
                            throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,
                                $this->file,$model,$position,$this->model);
                        }
                        foreach($records as $record) {
                            $this->relationsFor($record,self::PLAYER_DOMAINS,'teamEventBuild',$model);
                        }
                    }
                    break;
            }
        }
    }

    /**
     * @param array $record
     * @param array $domains
     * @param string $buildFn
     * @param string|null $model
     * @throws AppException
     */
    public function relationsFor(array $record, array $domains, string $buildFn, string $model=null)
    {
        $cache = [];
        foreach($record as $keyPos=>$dataPos) {
            list($key,$position) = explode('|',$keyPos);
            if (!in_array($key, $domains)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                    $key, $position, $domains);
            }
            $cache[$key]=$dataPos;
        }
        $this->$buildFn($cache,$model);
    }


    /**
     * @param array $cache
     * @throws AppException
     */
    public function teamPersonBuild(array $cache)
    {
        $typeCollection = array_keys($this->domain['type']);
        $teamPosition = $cache['type'];
        list($type, $typePos) = explode('|', $teamPosition);
        if (!in_array($type, $typeCollection)) {
            throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                $type, $typePos, $typeCollection);
        }
        $statusCollection = array_keys($this->domain['status']);
        list($status, $statusPos) = explode('|', $cache['status']);
        if (!in_array($status,$statusCollection)) {
            throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                $status, $statusPos, $statusCollection);
        }

        $sexCollection = array_keys($this->domain['sex']);
        foreach ($cache['sex'] as $teamSexPosition) {
            list($sex, $sexPos) = explode('|', $teamSexPosition);
            if (!in_array($sex, $sexCollection)) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                    $sex, $sexPos, $sexCollection);
            }
            list($ageRange, $ageRangePos) = explode('|', $cache['age']);
            if (preg_match('/(?P<lb>\d+)\-(?P<ub>\d+)/', $ageRange, $matches)) {
                if ($matches['lb'] > $matches['ub']) {
                    throw new AppException(AppExceptionCodes::INVALID_RANGE, $this->file,
                        $ageRange, $ageRangePos);
                }
            } else {
                throw new AppException(AppExceptionCodes::INVALID_RANGE, $this->file,
                    $ageRange, $ageRangePos);
            }
            list($lbStr, $ubStr) = explode('-', $ageRange);
            $lb = intval($lbStr);
            $ub = intval($ubStr);
            if ($lb > $ub) {
                throw new AppException(AppExceptionCodes::INVALID_RANGE,
                    $this->file, $ageRange, $ageRangePos);
            }
            $proficiencyCollection = array_keys($this->domain['proficiency']);
            foreach ($cache['proficiency'] as $proficiencyPosition => $eligiblePartnerProficiencies) {
                list($proficiency, $proficiencyPos) = explode('|', $proficiencyPosition);

                if (!in_array($proficiency, $proficiencyCollection)) {
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                        $proficiency, $proficiencyPos, $proficiencyCollection);
                }
                $personProficiencyList = $this->checkPersonProficiencies($eligiblePartnerProficiencies);
                $description =
                    ['type' => $type, 'status' => $status, 'sex' => $sex, 'proficiency' => $proficiency];

                $this->addTeamPersonRelations($description,[$lb, $ub],$personProficiencyList,$proficiencyPos);
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @throws AppException
     */
    private function checkPersonProficiencies( $data)
    {

        $collection = array_keys($this->domain['proficiency']);
        $return = [];
        foreach ($data as $valuePosition) {
            list($value, $position) = explode('|', $valuePosition);
            if (!isset($this->domain['proficiency'][$value])) {
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                    $value, $position, $collection);
            }
            $return[] = $value;
        }
        return $return;
    }

    /**
     * @param array $description
     * @param array $ageRange
     * @param array $partnerProficiencies
     * @param string $position
     * @throws AppException
     */
    private function addTeamPersonRelations(array $description,
                                            array $ageRange,
                                            array $partnerProficiencies,
                                            string $position)

    {
      for($year=$ageRange[0];$year<=$ageRange[1];$year++){
          $descriptionL1 = $description;
          $descriptionL1['age']='Y'.str_pad($year,2, "0", STR_PAD_LEFT);
          $statusList = explode('-',$description['status']);
          switch(count($statusList)) {
              case 1:
                  $this->buildSoloTeam($descriptionL1,$year,$partnerProficiencies, $position);
                  break;
              case 2:
                  $this->buildCoupleTeam($descriptionL1,$year,$partnerProficiencies, $position);
          }
          //TODO: Throw unhandled situation
      }
    }


    /**
     * @param $description
     * @param $year
     * @param $partnerProficiencies
     * @param string $position
     * @throws AppException
     */
    public function buildSoloTeam($description,$year,$partnerProficiencies,string $position) {

        if(count($partnerProficiencies)>0){
            throw new AppException(AppExceptionCodes::EMPTY_ARRAY_EXPECTED,
                $this->file, $partnerProficiencies[0],$position);
        }
        $teamSolo=$this->competition->buildTeamSolo($description,$year);
        $fileName='tp'.str_pad(++$this->fileNumber,7,'0',STR_PAD_LEFT);
        yaml_emit_file($fileName,$teamSolo);
    }

    /**
     * @param $description
     * @param $year
     * @param $partnerProficiencies
     * @param $position
     * @throws AppException
     */
    public function buildCoupleTeam(array $description, int $year,array $partnerProficiencies,string $position)
    {
        if(!count($partnerProficiencies)){
            throw new AppException(AppExceptionCodes::PARTNER_VALUES,
                $this->file,$description['proficiency'],$position);
        }
        $collection = array_keys($this->domain['proficiency']);
        foreach($partnerProficiencies as $proficiency){
            if(!in_array($proficiency, array_keys($collection))){
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                    $proficiency,$position,$collection);
            }
        }
        /*@var array */
        $teamPersons=$this->competition->buildTeamPersons($description,$year,$partnerProficiencies);
        $filename = 'tp'.str_pad(++$this->fileNumber,7,'0',STR_PAD_LEFT);
        yaml_emit_file('/tmp/gads/team-person/'.$filename.'.yaml', $teamPersons);
    }

    /**
     * @param array $cache
     * @param string|null $model
     * @throws AppException
     */
    public function teamEventBuild(array $cache, string $model=null) {

        if(!is_array($cache['type'])) {
            list($typeScaler,$typeScalerPos)=explode('|',$cache['type']);
            throw new AppException(AppExceptionCodes::INDEXED_ARRAY_EXPECTED,$this->file,
                        $typeScaler,$typeScalerPos);
        }
        foreach($cache['type'] as $teamTypePosition=>$eventTypePosition){
            list($teamType,$teamTypePos) = explode('|',$teamTypePosition);
            $teamTypeCollection=array_keys($this->domain['type']);
            if(!in_array($teamType,$teamTypeCollection)){
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,$this->file,
                    $teamType,$teamTypePos, $teamTypeCollection);
            }
            $eventTypeCollection=array_keys($this->value[$model]['type']);
            list($eventType,$eventTypePos) = explode('|',$eventTypePosition);
            if(!in_array($eventType,$eventTypeCollection)){
                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION,$this->file,
                    $eventType,$eventTypePos, $eventTypeCollection);
            }
            if(!is_array($cache['status'])) {
                list($statusScaler,$statusScalerPos)=explode('|',$cache['status']);
                throw new AppException(AppExceptionCodes::INDEXED_ARRAY_EXPECTED,$this->file,
                    $statusScaler,$statusScalerPos);

            }
            foreach($cache['status'] as $teamStatusPosition=>$eventStatusPosition){
                $teamStatusCollection = array_keys($this->domain['status']);
                list($teamStatus,$teamStatusPos) = explode('|',$teamStatusPosition);
                if(!in_array($teamStatus,$teamStatusCollection)){
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                        $teamStatus,$teamStatusPos,$teamStatusCollection);
                }
                $eventStatusCollection = array_keys($this->value[$model]['status']);
                list($eventStatus,$eventStatusPos) = explode('|',$eventStatusPosition);
                if(!in_array($eventStatus,$eventStatusCollection)) {
                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                        $eventStatus,$eventStatusPos,$eventStatusCollection);
                }
                if(!is_array($cache['sex'])) {
                    list($sexScaler,$sexScalerPos)=explode('|',$cache['sex']);
                    throw new AppException(AppExceptionCodes::INDEXED_ARRAY_EXPECTED,$this->file,
                        $sexScaler,$sexScalerPos);

                }
                foreach($cache['sex'] as $teamSexPosition=>$eventSexPosition) {
                    $teamSexCollection = array_keys($this->domain['sex']);
                    list($teamSex,$teamSexPos) = explode('|',$teamSexPosition);
                    if(!in_array($teamSex,$teamSexCollection)){
                        throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                            $teamSex,$teamSexPos,$teamSexCollection);
                    }
                    $eventSexCollection = array_keys($this->value[$model]['sex']);
                    list($eventSex,$eventSexPos) = explode('|',$eventSexPosition);
                    if(!in_array($eventSex,$eventSexCollection)) {
                        throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                            $eventSex,$eventSexPos,$eventSexCollection);
                    }
                    if(!isset($this->event[$eventType][$eventStatus][$eventSex])) {
                        $expectedEventSex = array_keys($this->event[$eventType][$eventStatus]);
                        throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                            $eventSex,$eventSexPos,$expectedEventSex);
                    }
                    if(!is_array($cache['age'])) {
                        list($ageScaler,$ageScalerPos)=explode('|',$cache['age']);
                        throw new AppException(AppExceptionCodes::INDEXED_ARRAY_EXPECTED,$this->file,
                            $ageScaler,$ageScalerPos);

                    }

                    foreach($cache['age'] as $teamAgePosition=>$eventAgeListPosition){
                        $teamAgeCollection = array_keys($this->domain['age']);
                        list($teamAge,$teamAgePos) = explode('|',$teamAgePosition);
                        if(!in_array($teamAge,$teamAgeCollection)){
                            throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                                $teamAge, $teamAgePos, $teamAgeCollection);
                        }
                        if(!is_array($cache['proficiency'])) {
                            list($proficiencyScaler,$proficiencyScalerPos)=explode('|',$cache['proficiency']);
                            throw new AppException(AppExceptionCodes::INDEXED_ARRAY_EXPECTED,$this->file,
                                $proficiencyScaler,$proficiencyScalerPos);
                        }
                        foreach($cache['proficiency'] as $teamProficiencyPosition=>$eventProficiencyListPosition){
                            $teamProficiencyCollection = array_keys($this->domain['proficiency']);
                            list($teamProficiency, $teamProficiencyPos) = explode('|',$teamProficiencyPosition);
                            if(!in_array($teamProficiency, $teamProficiencyCollection)) {
                                throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                                    $$teamProficiency,$teamProficiencyPos, $teamProficiencyCollection);
                            }
                            $team = ['type'=>$teamType,'status'=>$teamStatus,
                                     'sex'=>$teamSex,'age'=>$teamAge,'proficiency'=>$teamProficiency];
                            $event = ['type'=>$eventType,'status'=>$eventStatus, 'sex'=>$eventSex];
                            $eventHeaderList = [];
                            foreach($eventAgeListPosition as $eventAgePosition){
                                $eventAgeCollection=array_keys($this->value[$model]['age']);
                                list($eventAge,$eventAgePos) = explode('|',$eventAgePosition);
                                if(!in_array($eventAge,$eventAgeCollection)){
                                    throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                                        $eventAge,$eventAgePos,$eventAgeCollection);
                                }
                                $eventL1=$event;
                                $eventL1['age']=$eventAge;
                                foreach($eventProficiencyListPosition as $eventProficiencyPosition) {
                                    $eventProficiencyCollection=array_keys($this->value[$model]['proficiency']);
                                    list($eventProficiency,$eventProficiencyPos) =
                                        explode('|',$eventProficiencyPosition);
                                    if(!in_array($eventProficiency,$eventProficiencyCollection)) {
                                        throw new AppException(AppExceptionCodes::NOT_IN_COLLECTION, $this->file,
                                            $eventProficiency, $eventProficiencyPos, $eventProficiencyCollection);
                                    }
                                    $eventL2=$eventL1;
                                    $eventL2['proficiency']=$eventProficiency;
                                    array_push($eventHeaderList,$eventL2);
                                }
                            }
                            $this->buildToTmpFiles($team,$eventHeaderList);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $team
     * @param array $eventHeaderList
     */
    private function buildToTmpFiles(array $team, array $eventHeaderList)
    {
        $eventList = [];
        $file='te'.str_pad($this->fileNumber++,'7','0',STR_PAD_LEFT);
        foreach($eventHeaderList as $eventHeader){
            $type = $eventHeader['type'];
            $status= $eventHeader['status'];
            $sex = $eventHeader['sex'];
            $age = $eventHeader['age'];
            $proficiency = $eventHeader['proficiency'];
            foreach(array_keys($this->event[$type][$status][$sex]) as $model) {
                $styleEvents=& $this->event[$type][$status][$sex][$model][$age][$proficiency];
                if(isset($this->event[$type][$status][$sex][$model][$age][$proficiency])){
                    foreach($styleEvents as $style=>$events){
                        foreach($events['events'] as $event) {
                            array_push($eventList, $event);
                        }
                    }
                }
            }
        }
        if(count($eventList)){
            yaml_emit_file('/tmp/gads/team-event/'.$file.'.yaml',['team'=>$team,'events'=>$eventList]);
        }
    }
}
