<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/29/18
 * Time: 6:20 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

}