<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:28 PM
 */

namespace App\Repository\Models;


use App\Entity\Models\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamRepository extends ServiceEntityRepository
{
    /** @var PersonRepository */

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function create(array $description) : Team
    {
        $team = new Team();
        $team->setDescription($description);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();
        return $team;
    }

    public function read(int $id) : ?Team
    {
        /** @var Team $team */
        $team = $this->find($id);
        return $team;
    }

    public function readFilter(Person $person1, Person $person2=null) : ?array
    {
        $qb = $this->createQueryBuilder('team');
        $qb->select('team','person','event')
            ->innerJoin('team.person','person');
        $description1 = $person1->getDescription();
        if($person2){

        }
    }


    public function update(Team $new)
    {
        /** @var Team $old */
        $old = $this->find($new->getId());
        $old->setDescription($new->getDescription())
            ->setPerson($new->getPerson())
            ->setEvent($new->getEvent());
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($old);
        $em->flush();
    }

    public function delete(int $id)
    {
        /** @var Team $team */
        $team = $this->find($id);
        $this->remove($team);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();
    }

    public function remove(Team $team)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->remove($team);
    }

    public function fetchQuickSearch()
    {
        $results = $this->findAll();
        /** @var Team $team */
        $lookup = [];
        foreach($results as $team) {
            $data=$team->getDescription();
            $type = $data['type'];
            $status = $data['status'];
            $sex = $data['sex'];
            $age = $data['age'];
            $proficiency = $data['proficiency'];
            if(!isset($lookup[$type])) {
                $lookup[$type] = [];
            }
            if(!isset($lookup[$type][$status])) {
                $lookup[$type][$status]=[];
            }
            if(!isset($lookup[$type][$status][$sex])) {
                $lookup[$type][$status][$sex]=[];
            }
            if(!isset($lookup[$type][$status][$sex][$age])) {
                $lookup[$type][$status][$sex][$age]=[];
            }
            $lookup[$type][$status][$sex][$age][$proficiency] = $team;
        }
        return $lookup;
    }
}