<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 6:26 PM
 */

namespace App\Repository\Models;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Models\Model;

class ModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    public function create(string $name): Model
    {
        /** @var Model $model */
        $model = new Model();
        $model->setName($name)
            ->setCreatedAt(new \DateTime('now'));
        $em = $this->getEntityManager();
        $em->persist($model);
        $em->flush();
        return $model;
    }

    public function read(?int $id): Model
    {
        $em = $this->getEntityManager();
        /** @var  $result */
        $result = $em->find('Model', $id);
        return $result;
    }

    private function list(): array
    {

    }




   public function update(Model $model) {
       $em = $this->getEntityManager();

   }

    /**
     * @param Model $model
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Model $model) {
        $em = $this->getEntityManager();
        $em->remove($model);
        $em->flush();
    }

}