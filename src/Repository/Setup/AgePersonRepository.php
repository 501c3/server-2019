<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:17 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\AgePerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AgePersonRepository extends ServiceEntityRepository
{
   public function __construct(ManagerRegistry $registry)
   {
       parent::__construct($registry, AgePerson::class);
   }
}