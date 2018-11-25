<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:28 PM
 */

namespace App\Repository\Models;

use App\Entity\Models\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Value;
use Doctrine\ORM\EntityManagerInterface;

class ValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Value::class);
    }

    /**
     * @param string $name
     * @param string $abbr
     * @param Domain $domain
     * @return Value
     */
    public function create(string $name,string $abbr, Domain $domain) : Value
    {
        $value = new Value;
        $value->setName($name)
                ->setAbbr($abbr)
                ->setDomain($domain);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($value);
        $em->flush();
        return $value;
    }

    /**
     * @param int $id
     * @return Value|null
     */
    public function read(int $id) : ?Value
    {
        /** @var  Value|null $result */
        $result = $this->find($id);
        return $result;
    }

    /**
     * @param Domain|null $domain
     * @return array|null
     */
    public function readMulti(Domain $domain=null) : ?array
    {
        if(!$domain){
            $result = $this->findAll();
            return count($result)>0?$result:null;
        }
        $qb = $this->createQueryBuilder('value');
        $qb->select('value','domain')
            ->innerJoin('value.domain','domain')
            ->where('domain=:domain');
        $query = $qb->getQuery();
        $query->setParameter(':domain',$domain);
        $result = $query->getResult();
        return $result;
    }

    /**
     * @param Value $new
     */
    public function update(Value $new)
    {
        /** @var Value $old */
        $old = $this->find($new->getId());
        $old->setName($new->getName())
            ->setAbbr($new->getAbbr())
            ->setDomain($new->getDomain());
        /** @var EntityManagerInterface $em */
        $em=$this->getEntityManager();
        $em->persist($old);
        $em->flush();
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $value=$this->find($id);
        /** @var EntityManagerInterface $em */
        $em=$this->getEntityManager();
        $em->remove($value);
        $em->flush();
    }

    /**
     * @param Value $value
     */
    public function remove(Value $value)
    {
        /** @var EntityManagerInterface $em */
        $em=$this->getEntityManager();
        $em->remove($value);
        $em->flush();
    }

    /**
     * @return array
     */
    public function fetchQuickSearch() : array
    {
        $qb = $this->createQueryBuilder('value');
        $qb->select('value','domain')
            ->innerJoin('value.domain','domain');
        $query = $qb->getQuery();
        $results = $query->getResult();
        $hash = [];
        /** @var Value $result */
        foreach($results as $result){
            $domName= $result->getDomain()->getName();
            if(!isset($hash[$domName])) {
                $hash[$domName]=[];
            }
            $valName = $result->getName();
            $hash[$domName][$valName] = $result;
        }
        return $hash;
    }
}