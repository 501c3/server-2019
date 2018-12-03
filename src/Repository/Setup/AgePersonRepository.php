<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:17 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\AgePerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AgePersonRepository extends ServiceEntityRepository
{
   public function __construct(ManagerRegistry $registry)
   {
       parent::__construct($registry, AgePerson::class);
   }

    /**
     * @param array $description
     * @return AgePerson
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $description) {
       $em = $this->getEntityManager();
       $agePerson = new AgePerson();
       $agePerson->setDescribe($description);
       $em->persist($agePerson);
       $em->flush();
       return $agePerson;
    }

    /**
     * @return array
     */
   public function fetchQuickSearch():array
   {
       $results = $this->findAll();
       $arr = [];
       /** @var AgePerson $result */
       foreach($results as $result) {
           $describe = $result->getDescribe();
           $type = $describe['type'];
           $status = $describe['status'];
           $sex = $describe['sex'];
           $years = $describe['years'];
           if(!isset($arr[$type])) {
               $arr[$type]=[];
           }
           if(!isset($arr[$type][$status])) {
               $arr[$type][$status] = [];
           }
           if(!isset($arr[$type][$status][$sex])) {
               $arr[$type][$status][$sex]=[];
           }
           $arr[$type][$status][$sex][$years]=$result;
       }
       return $arr;
   }
}