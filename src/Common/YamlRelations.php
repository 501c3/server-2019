<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/14/18
 * Time: 9:52 PM
 */

namespace App\Common;



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
     * @var array
     * Contains only the latest  teamPerson collections
     */
    private $teamPerson = [];
    /**
     * @var array
     */
    //private $teamEvent = [];
//    private $dbPerson;
//    private $dbTeam;
//    private $dbEvent;

    /**
     * YamlRelations constructor.
     */
    public function __construct()
    {
        $this->competition = new GeorgiaDanceSport();
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

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws \Exception
     */
    public function declareRelations(string $file)
    {
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
//                    foreach($recordList as $record) {
//                        //$this->relationsFor($record,self::EVENT_DOMAINS,'teamEventBuild');
//                    }
                    break;
            }
        }
        //TODO: Holds the latest teamPerson build before inserting into database.  Here for testing purposes.
        return $this->teamPerson;
    }

    /**
     * @param array $record
     * @param array $domains
     * @param string $buildFn
     * @throws AppException
     * @throws \Exception
     */
    public function relationsFor(array $record, array $domains, string $buildFn)
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
        $positions = YamlPosition::isolate(array_keys($record),YamlPosition::POSITION);
        $this->$buildFn($cache,$positions);
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
          $descriptionL1['age']='Y'.str_pad($year,2, "0");
          $statusList = explode('-',$description['status']);
          switch(count($statusList)) {
              case 1:
                 // $this->buildSoloTeam($descriptionL1,$year,$partnerProficiencies, $position);
                  break;
              case 2:
                  $this->buildCoupleTeam($descriptionL1,$year,$partnerProficiencies, $position);
          }
          //TODO: Throw unhandled situation
      }
    }


    /**
     * @param $description
     * @param $partnerProficiencies
     * @throws AppException
     */
    public function buildSoloTeam($description,$partnerProficiencies) {

        if(count($partnerProficiencies)){
            $proficiencyPosition = $partnerProficiencies[0];
            list($proficiency,$position) = explode('|',$proficiencyPosition);
            throw new AppException(AppExceptionCodes::EMPTY_ARRAY_EXPECTED,
                $this->file, $proficiency,$position);
        }
        var_dump($description);die;
        //TODO and possible solo competitions
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
        $teamsCouples=$this->competition->buildTeamsCouples($description,$year,$partnerProficiencies);
        $this->teamPerson=$teamsCouples;
//        foreach($teamsCouples as $singleTeam) {
//           //TODO: DbInterface Implemented Here.
//        }
    }

    public function teamEventBuild(array $cache) {
        var_dump($cache);die;
    }
}
