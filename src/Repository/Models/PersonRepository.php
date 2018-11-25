<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:26 PM
 */

namespace App\Repository\Models;


use App\Entity\Models\Value;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Person;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param array $description
     * @param $years
     * @param array $values
     * @return Person
     */
    public function create(array $description, $years, array $values) : Person
    {
        $person = new Person();
        $person->setDescription($description)
                ->setAge($years)
                ->setValue(new ArrayCollection($values));
        /** @var EntityManagerInterface $em */
        $em=$this->getEntityManager();
        $em->persist($person);
        $em->flush();
        return $person;
    }

    /**
     * @param int $id
     * @return Person|null
     */
    public function read(int $id) : ?Person
    {
        /** @var Person $result */
        $result=$this->find($id);
        return $result;
    }

    /**
     * @param array $values
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function readMulti(array $values, int $offset = null, int $limit = null) : array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('person');
        $qb->select('person','value')
            ->innerJoin('person.value','value')
            ->where('value=:value');
        $value = array_shift($values);
        $query = $qb->getQuery();
        $query->setParameter(':value',$value);
        $list = $query->getResult();
        $qb->andWhere('person IN (:list)');
        if($offset && $limit) {
          $qb->setFirstResult($offset)
                ->setMaxResults($limit);
        }
        return $this->readRecursive($list, $values, $qb->getQuery());
    }


    /**
     * @param array $list
     * @param array $values
     * @param Query $query
     * @return array
     */
    private function readRecursive(array $list, array $values, Query $query) : ?array
    {
        if(count($values)==0){
            return count($list)>0?$list:null;
        }
        $value = array_shift($values);
        $query->setParameter(':value',$value);
        $query->setParameter(':list',$list);
        $nextList = $query->getResult();
        return $this->readRecursive($nextList,$values,$query);
    }

    /**
     * @param Person $new
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Person $new)
    {
        /** @var Person $old */
        $old=$this->find($new->getId());
        $old->setValue($new->getValue())
            ->setAge($new->getAge())
            ->setTeam($new->getTeam())
            ->setDescription($new->getDescription());
        $em = $this->getEntityManager();
        $em->persist($old);
        $em->flush();
    }

    /**
     * @param int $id
     * @throws ORMException
     */
    public function delete(int $id)
    {
        $entity = $this->find($id);
        $em = $this->getEntityManager();
        $em->remove($entity);
    }

    /**
     * @param Person $person
     * @throws ORMException
     */
    public function remove(Person $person)
    {
        $em = $this->getEntityManager();
        $em->remove($person);
    }

    /**
     * @return array
     */
    public function fetchQuickSearch() : array
    {
        $qb = $this->createQueryBuilder('person');
        $qb->select('person','value','domain')
            ->innerJoin('person.value','value')
            ->innerJoin('value.domain','domain');
        $query = $qb->getQuery();
        $lookup = [];
        $results = $query->getResult();
        /** @var Person $person */
        foreach($results as $person) {
            $idx = $this->indexedArray($person->getValue());
            $years = $person->getAge();
            $this->buildLookup($lookup, $years, $idx, $person);
        }
        return $lookup;
    }

    /**
     * @param Collection $valueList
     * @return array
     */
    private function indexedArray($valueList) : array
    {
        /** @var Value|null $item */
        $hash = [];
        $value = $valueList->first();
        while ($value) {
            $domain = $value->getDomain()->getName();
            $hash[$domain] = $value->getName();
            $value = $valueList->next();
        }
        return $hash;
    }

    /**
     * @param array $lookup
     * @param int $years
     * @param array $idx
     * @param Person $person
     */
    private function buildLookup(array &$lookup, int $years, array $idx, Person $person)
    {

        if(!isset($lookup[$idx['type']])) {
            $lookup[$idx['type']] = [];
        }
        if(!isset($lookup[$idx['type']][$idx['status']])) {
            $lookup[$idx['type']][$idx['status']] = [];
        }
        if(!isset($lookup[$idx['type']][$idx['status']][$idx['sex']])) {
            $lookup[$idx['type']][$idx['status']][$idx['sex']] = [];
        }
        if(!isset($lookup[$idx['type']][$idx['status']][$idx['sex']][$years])) {
            $lookup[$idx['type']][$idx['status']][$idx['sex']][$years] = [];
        }
        if(!isset($lookup[$idx['type']][$idx['status']][$idx['sex']][$years])) {
            $lookup[$idx['type']][$idx['status']][$idx['sex']][$years] = [];
        }
        $lookup[$idx['type']][$idx['status']][$idx['sex']][$years][$idx['proficiency']]=$person;
    }




}