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
use App\Entity\Models\Event;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

}