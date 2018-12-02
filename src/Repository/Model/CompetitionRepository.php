<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:16 PM
 */

namespace App\Repository\Model;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Model\Competition;
use Doctrine\ORM\EntityManagerInterface;

class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    public function create(string $name) : Competition
    {
        $competition = new Competition();
        $competition->setName($name)
                    ->setUpdated(new \DateTime('now'));
        /** @var EntityManagerInterface $em */
        $em=$this->getEntityManager();
        $em->persist($competition);
        $em->flush();
        return $competition;
    }

    /**
     * @param int $id
     * @return Competition|null
     */
    public function read(int $id) : ?Competition
    {
        /** @var Competition|null $result */
        $result = $this->find($id);
        return $result;
    }

    public function readMulti() : ?array
    {
        $result = $this->findAll();
        return $result;
    }

    public function update(Competition $new)
    {
        $old = $this->find($new->getId());
        $old->setName($new->getName())
            ->setUpdated(new \DateTime('now'));
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($old);
        $em->flush();
    }

    public function delete(int $id)
    {
        $competition = $this->find($id);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->remove($competition);
        $em->flush();
    }

    public function remove(Competition $competition)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->remove($competition);
        $em->flush();
    }
}