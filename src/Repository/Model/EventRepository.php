<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:26 PM
 */

namespace App\Repository\Model;


use App\Entity\Models\Model;
use App\Entity\Models\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Event;
use Doctrine\ORM\Query;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param array $description
     * @param Model $model
     * @param ArrayCollection $values
     * @return Event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $description, Model $model, ArrayCollection $values)
    {
        $event = new Event();
        $event->setDescription($description)
            ->setModel($model)
            ->setValue($values);
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
        return $event;
    }

    /**
     * @param int $id
     * @return Event|null
     */
    public function read(int $id) : ?Event
    {
        /** @var Event|null $result */
        $result = $this->find($id);
        return $result;
    }

    /**
     * @param Team $team
     * @param ArrayCollection $value
     * @return array|null
     */
    public function readFilter(Team $team, ArrayCollection $value) : ?array
    {
        $qb = $this->createQueryBuilder('event');
        $qb->select('event','value')
            ->innerJoin('event.team','team')
            ->innerJoin('event.value','value')
            ->where('team=:team')
            ->andWhere('value=:value');
        $array = $value->toArray();
        $value = array_shift($array);
        $query = $qb->getQuery();
        $query->setParameter(':team',$team);
        $query->setParameter(':value',$value);
        $initial = $query->getResult();
        $qb->andWhere('event in (:list)');
        $nextQuery = $qb->getQuery();
        $result = $this->readRecursive($nextQuery,$array,$initial);
        return $result;
    }

    /**
     * @param Query $query
     * @param array $values
     * @param array $initial
     * @return array
     */
    private function readRecursive(Query $query, array &$values, array $initial)
    {
        if(count($values) == 0) {
            return $initial;
        }
        $value = array_shift($values);
        $query->setParameter(':value',$value);
        $query->setParameter(':list',$initial);
        $nextResults = $query->getResult();
        $result = $this->readRecursive($query, $values, $nextResults);
        return $result;
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(int $id)
    {
        $event=$this->find($id);
        $em = $this->getEntityManager();
        $em->remove($event);
        $em->flush();
    }

    /**
     * @param Event $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Event $event)
    {
        $em = $this->getEntityManager();
        $em->remove($event);
        $em->flush($event);
    }

    /**
     * @return array
     */
    public function fetchQuickSearch(): array
    {
        $lookup = [];
        $events = $this->findAll();
        /** @var Event $event */
        foreach($events as $event) {
            $description = $event->getDescription();
            $type = $description['type'];
            $status = $description['status'];
            $sex = $description['sex'];
            $model = $description['model'];
            $age = $description['age'];
            $proficiency = $description['proficiency'];
            $style = $description['style'];
            if(!isset($lookup[$type])) {
                $lookup[$type]=[];
            }
            if(!isset($lookup[$type][$status])) {
                $lookup[$type][$status]=[];
            }
            if(!isset($lookup[$type][$status][$sex])) {
                $lookup[$type][$status][$sex]=[];
            }
            if(!isset($lookup[$type][$status][$sex][$model])) {
                $lookup[$type][$status][$sex][$model]=[];
            }
            if(!isset($lookup[$type][$status][$sex][$model][$age])) {
                $lookup[$type][$status][$sex][$model][$age]=[];
            }
            if(!isset($lookup[$type][$status][$sex][$model][$age][$proficiency])) {
                $lookup[$type][$status][$sex][$model][$age][$proficiency]=[];
            }
            if(!isset($lookup[$type][$status][$sex][$model][$age][$proficiency][$style])) {
                $lookup[$type][$status][$sex][$model][$age][$proficiency][$style]=[];
            }
            $ptr = & $lookup[$type][$status][$sex][$model][$age][$proficiency];
            array_push($ptr, $event);
        }
        return $lookup;
    }

}