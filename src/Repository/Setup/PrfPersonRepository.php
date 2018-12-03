<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:21 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\PrfPerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PrfPersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrfPerson::class);
    }

    /**
     * @param array $describe
     * @return PrfPerson
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $describe)
    {
        $prfPerson = new PrfPerson();
        $prfPerson->setDescribe($describe);
        $em = $this->getEntityManager();
        $em->persist($prfPerson);
        $em->flush();
        return $prfPerson;
    }

    /**
     * @return array
     */
    public function fetchQuickSearch():array
    {
        $results = $this->findAll();
        $arr = [];
        /** @var PrfPerson $result */
        foreach($results as $result) {
            $describe = $result->getDescribe();
            $type = $describe['type'];
            $status = $describe['status'];
            $sex = $describe['sex'];
            $proficiency = $describe['proficiency'];
            if(!isset($arr[$type])) {
                $arr[$type]=[];
            }
            if(!isset($arr[$type][$status])) {
                $arr[$type][$status]=[];
            }
            if(!isset($arr[$type][$status][$sex])) {
                $arr[$type][$status][$sex] = [];
            }
            $arr[$type][$status][$sex][$proficiency]=$result;
        }
        return $arr;
    }
}