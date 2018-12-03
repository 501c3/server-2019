<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/1/18
 * Time: 9:31 PM
 */

namespace App\Common;


use App\Entity\Setup\AgePerson;
use App\Entity\Setup\PrfPerson;
use App\Repository\Setup\AgePersonRepository;
use App\Repository\Setup\PrfPersonRepository;
use Doctrine\ORM\EntityManagerInterface;

class YamlDbSetupPerson extends YamlDbSetupBase
{
    const PERSON_DOMAIN_KEYS = ['type','status','sex','age','proficiency'];

    private $person = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @param string $file
     * @return array
     * @throws AppException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function parsePersons(string $file)
    {
        $agePrfPositionArray = YamlPosition::yamlAddPosition($file);
        foreach($agePrfPositionArray as $records){
            foreach($records as $keyPosition=>$valueList) {
                list($key,$position) = explode('|',$keyPosition);
                if(!in_array($key,self::PERSON_DOMAIN_KEYS)) {
                    throw new AppException(AppExceptionCodes::FOUND_BUT_EXPECTED,
                        [$file,$key,$position,self::PERSON_DOMAIN_KEYS]);
                }
            }
            $keysPositions = array_keys($records);
            $keysFound = YamlPosition::isolate($keysPositions);
            $difference = array_diff(self::PERSON_DOMAIN_KEYS, $keysFound);
            if (count($difference)) {
                throw new AppException(AppExceptionCodes::MISSING_KEYS,
                    [$file, $difference, $keysPositions]);
            }
            $cache = [];
            foreach ($records as $keyPosition => $dataPosition) {
                list($key) = explode('|', $keyPosition);
                $cache[$key] = $this->personValuesCheck($file, $key, $dataPosition);
            }
            $this->personValuesBuild($cache);
        }
        return $this->person;
    }

    /**
     * @param string $file
     * @param string $key
     * @param $valuesPositions
     * @return array|string
     * @throws AppException
     * @throws \Exception
     *
     */
    private function personValuesCheck(string $file, string $key, $valuesPositions)
    {
        switch($key) {
            case 'type':
            case 'status':
                list($value,$position) = explode('|',$valuesPositions);
                if(!isset($this->value[$key][$value])) {
                    throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                        [$file, $value, $position]);
                }
                break;
            case 'age':
                list($range,$position) = explode('|',$valuesPositions);
                $result = preg_match('/(?P<lower>\w+)\-(?P<upper>\w+)/',$range, $bound);
                if(!$result ||
                    (!is_numeric($bound['lower'])) || !is_numeric($bound['upper']) ||
                    ($bound['lower']>$bound['upper'])) {
                    throw new AppException(AppExceptionCodes::INVALID_RANGE, [$file,$range,$position]);
                }
                break;
            case 'sex':
            case 'proficiency':
                foreach($valuesPositions as $valuePos) {
                    list($value,$position)=explode('|',$valuePos);
                    /** @var YamlDbSetupBase $this */
                    if(!isset($this->value[$key][$value])) {
                        throw new AppException(AppExceptionCodes::UNRECOGNIZED_VALUE,
                            [$file,$value,$position]);
                    }
                }
        }
        return YamlPosition::isolate($valuesPositions);
    }

    /**
     * @param array $cache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function personValuesBuild(array $cache)
    {
        $type = $cache['type'];
        $status = $cache['status'];
        if(!isset($this->person[$type])) {
            $this->person[$type]=[];
        }
        if(!isset($this->person[$type][$status])) {
            $this->person[$type][$status] = [];
        }
        foreach($cache['sex'] as $sex) {
            if(!isset($this->person[$type][$status][$sex])){
                $this->person[$type][$status][$sex] = [];
            }
            $ages = $this->agePersonValuesBuild($type,$status,$sex,$cache['age']);
            $prfs = $this->prfPersonValuesBuild($type,$status,$sex, $cache['proficiency']);

            /** @var AgePerson $agePerson */
            foreach($ages as $years=>$agePerson) {
                /** @var PrfPerson $prfPerson */
                foreach($prfs as $proficiency=>$prfPerson) {
                    $agePerson->getPrfPerson()->set($proficiency,$prfPerson);
                }
            }

            $this->person[$type][$status][$sex]['prf'] = $prfs;

            $this->person[$type][$status][$sex]['age'] = $ages;
        }
        $this->entityManager->flush();
    }

    /**
     * @param $type
     * @param $status
     * @param $sex
     * @param $ageRange
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function agePersonValuesBuild($type,$status,$sex,$ageRange)
    {
        $arr = [];
        /** @var AgePersonRepository $repository */
        $repository = $this->entityManager->getRepository(AgePerson::class);
        list($s1,$s2) = explode('-',$ageRange);
        $lb = intval($s1);
        $ub = intval($s2);
        for($i = $lb; $i<=$ub; $i++) {
            $describe = ['type'=>$type,'status'=>$status,'sex'=>$sex, 'years'=>$i];
            $agePerson = $repository->create($describe);
            $agePerson->getValue()->set('type',$this->value['type'][$type]);
            $agePerson->getValue()->set('status',$this->value['status'][$status]);
            $agePerson->getValue()->set('sex',$this->value['sex'][$sex]);
            $arr[$i]=$agePerson;
        }
        return $arr;
    }

    /**
     * @param $type
     * @param $status
     * @param $sex
     * @param $proficiencies
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function prfPersonValuesBuild($type,$status,$sex,$proficiencies)
    {
        $arr = [];
        /** @var PrfPersonRepository $repository */
        $repository = $this->entityManager->getRepository(PrfPerson::class);
        foreach($proficiencies as $proficiency) {
            $describe = ['type'=>$type,'status'=>$status,'sex'=>$sex,'proficiency'=>$proficiency];
            $prfPerson = $repository->create($describe);
            foreach($describe as $key=>$value) {
                $prfPerson->getValue()->set($key,$this->value[$key][$value]);
            }
            $arr[$proficiency] = $prfPerson;
        }
        return $arr;
    }

    private function agePrfValRelations(& $ages, & $prfs)
    {

        /**
         * @var int $i
         * @var AgePerson $age
         */

        foreach($ages as $i=>$age) {
            /**
             * @var string  $proficiency
             * @var PrfPerson $prf
             */

            foreach($prfs as $proficiency=>$prf) {
                $age->getPrfPerson()->add($prf);
            }
        }
    }



}