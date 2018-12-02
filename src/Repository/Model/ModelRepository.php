<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:26 PM
 */

namespace App\Repository\Model;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Model;
use Doctrine\ORM\EntityManagerInterface;


class ModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    /**
     * @param string $name
     * @return Model
     */
    public function create(string $name): Model
    {
        /** @var Model $model */
        $model = new Model();
        $model->setName($name)
                ->setCreatedAt(new \DateTime('now'));
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($model);
        $em->flush();
        return $model;
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function read(int $id): ?Model
    {
        /** @var Model $result */
        $result = $this->find($id);
        return $result;

    }

    /**
     * @return array|null
     */
    public function readMulti() : ?array
    {
        $result = $this->findAll();
        return count($result)>0?$result:null;
    }

    /**
     * @param Model $new
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Model $new) {
        /** @var Model $old */
        $old = $this->find($new->getId());
        $old->setName($new->getName())
            ->setCreatedAt(new \DateTime('now'));
        $em = $this->getEntityManager();
        $em->persist($old);
        $em->flush($old);
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(int $id) {
        $model=$this->find($id);
        $em=$this->getEntityManager();
        $em->remove($model);
        $em->flush();
    }

    /**
     * @param Model $model
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Model $model) {
        $em=$this->getEntityManager();
        $em->remove($model);
        $em->flush();
    }

    public function fetchQuickSearch() {
        $results = $this->findAll();
        $lookup = [];
        foreach($results as $model) {
            $lookup[$model->getName()]=$model;
        }
        return $lookup;
    }
}