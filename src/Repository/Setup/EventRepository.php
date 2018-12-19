<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:20 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\Event;
use App\Entity\Setup\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param Model $model
     * @param array $describe
     * @param array $values
     * @return Event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Model $model, array $describe, array $values): Event
    {

        $em = $this->getEntityManager();
        $event = new Event();
        $event->setModel($model)
                ->setDescribe($describe);
        $collection = $event->getValue();
        foreach($values as $value) {
            $collection->add($value);
        }
        $em->persist($event);
        $em->flush();
        return $event;
    }

    public function fetchQuickSearch(Model $model)
    {
        $arr = [];
             $qb = $this->createQueryBuilder('event');
        $qb->select('event','model')
            ->innerJoin('event.model','model')
            ->where('model=:model');
        $query = $qb->getQuery();
        $query->setParameter(':model',$model);
        $results = $query->getResult();
        /** @var Event $result */
        foreach($results as $result) {
            $describe = $result->getDescribe();
            $type=$describe['type'];
            $status=$describe['status'];
            $sex=$describe['sex'];
            $age=$describe['age'];
            $proficiency = $describe['proficiency'];
            $style= $describe['style'];
            if(!isset($arr[$type])){
                $arr[$type]=[];
            }
            if(!isset($arr[$type][$status])){
                $arr[$type][$status]=[];
            }
            if(!isset($arr[$type][$status][$sex])) {
                $arr[$type][$status][$sex] = [];
            }
            if(!isset($arr[$type][$status][$sex][$age])){
                $arr[$type][$status][$sex][$age]=[];
            }
            if(!isset($arr[$type][$status][$sex][$age][$proficiency])){
                $arr[$type][$status][$sex][$age][$proficiency]=[];
            }
            if(!isset($arr[$type][$status][$sex][$age][$proficiency][$style])){
                $arr[$type][$status][$sex][$age][$proficiency][$style]=[];
            }
            array_push($arr[$type][$status][$sex][$age][$proficiency][$style],$result);
        }
        return $arr;
    }

}