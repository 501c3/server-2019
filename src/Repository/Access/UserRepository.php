<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/9/19
 * Time: 6:03 PM
 */

namespace App\Repository\Access;


use App\DataTransformer\RegistrationToUserTransformer;
use App\Entity\Access\User;
use App\Form\Model\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @var RegistrationToUserTransformer
     */
    private $transformer;

    public function __construct(ManagerRegistry $registry,
                                RegistrationToUserTransformer $transformer)
    {
        parent::__construct($registry,User::class);
        $this->transformer = $transformer;
    }

    /**
     * @param Registration $registration
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(Registration $registration) : User
    {
        $user = $this->transformer->transform($registration);
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }
}