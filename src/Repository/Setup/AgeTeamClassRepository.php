<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/30/18
 * Time: 7:30 PM
 */

namespace App\Repository\Setup;


use App\Entity\Setup\AgeTeamClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AgeTeamClassRepository extends ServiceEntityRepository
{
public function __construct(ManagerRegistry $registry)
{
    parent::__construct($registry, AgeTeamClass::class);
}
}