<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:27 PM
 */

namespace App\Repository\Model;


use App\Entity\Models\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Subevent;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class SubeventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subevent::class);
    }

    /**
     * @param array $description
     * @param Event $event
     * @return Subevent
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $description, Event $event)
    {
        $subevent = new Subevent();
        $subevent->setDescription($description)
                ->setEvent($event);
        $em = $this->getEntityManager();
        $em->persist($subevent);
        $em->flush();
        return $subevent;
    }

    public function read(int $id)
    {
        return $this->find($id);
    }

    public function readMulti(Event $event)
    {
        $qb = $this->createQueryBuilder('subevent');
        $qb->select('subevent','event')
            ->innerJoin('subevent.event','event')
            ->where('event = :event');
        $query = $qb->getQuery();
        $query->setParameter(':event',$event);
        $result = $query->getResult();
        return $result;
    }

    /**
     * @param Subevent $new
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Subevent $new)
    {
        $old = $this->find($new->getId());
        $old->setDescription($new->getDescription())
            ->setEvent($new->getEvent());
        $em = $this->getEntityManager();
        $em->persist($old);
        $em->flush();
    }

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(int $id)
    {
        $subevent = $this->find($id);
        $em = $this->getEntityManager();
        $em->remove($subevent);
        $em->flush();
    }

    /**
     * @param Subevent $subevent
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Subevent $subevent)
    {
        $em = $this->getEntityManager();
        $em->remove($subevent);
        $em->flush();
    }
}