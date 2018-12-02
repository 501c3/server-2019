<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:21 PM
 */

namespace App\Repository\Setup;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PrfPersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrfPersonRepository::class);
    }
}