<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 *
 * Date: 1/9/19
 * Time: 12:07 AM
 */

namespace App\Tests\Common;


use App\Common\YamlDbModelSubevent;
use App\Entity\Model\Subevent;
use App\Kernel;
use App\Repository\Model\SubeventRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class Code1900ModelSubevent extends KernelTestCase
{
    /** @var Kernel */
   protected static $kernel;

   /** @var YamlDbModelSubevent */
   protected static $yamlDbModelSubevent;

   /** @var EntityManagerInterface */
   protected static $emModel;

   public static function setUpBeforeClass()
   {
       (new Dotenv())->load(__DIR__.'/../../.env');
       self::$kernel = self::bootKernel();
       self::purgeAndLoad();
       /** @var EntityManagerInterface $em */
       self::$emModel= self::$kernel->getContainer()->get('doctrine.orm.model_entity_manager');
       self::$yamlDbModelSubevent = new YamlDbModelSubevent(self::$emModel);
   }


    /**
     * @param $dumpDirectory
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function loadSetupDump($dumpDirectory)
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        $conn = $em->getConnection();
        $conn->query('set GLOBAL net_buffer_length=3000000');
        $conn->query('SET foreign_key_checks = 0');
        $dumpFiles = self::findDumpFiles($dumpDirectory);
        foreach($dumpFiles as $file) {
            $sql = file_get_contents(($file));
            $conn->query($sql);
        }
        $conn->query('SET foreign_key_checks = 1');
        $conn->query('UNLOCK TABLES');
    }

    private static function findDumpFiles($pathfile)
    {
        $dumpFiles = [];
        if(is_dir($pathfile))  {
            $predump=scandir($pathfile);
            array_shift($predump);
            array_shift($predump);
            foreach($predump as $file){
                $parts = pathinfo($file);
                if($parts['extension']=='sql'){
                    $file=$pathfile.'/'.$parts['filename'].'.'.$parts['extension'];
                    $dumpFiles[]=$file;
                }
            }
        }
        return $dumpFiles;
    }

    public static function purgeDatabase(EntityManagerInterface $em)
   {
       $conn = $em->getConnection();
       $conn ->query('SET FOREIGN_KEY_CHECKS=0');
       $purger = new ORMPurger($em);
       $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
       $purger->purge();
       $conn->query('SET FOREIGN_KEY_CHECKS=1');
       $conn->query('UNLOCK TABLES');
   }

   public static function purgeAndLoad()
   {
       $container = self::$kernel->getContainer();
       /** @var EntityManagerInterface $emSetup */
       $emSetup = $container->get('doctrine.orm.setup_entity_manager');
       /** @var EntityManagerInterface $emModel */
       $emModel = $container->get('doctrine.orm.model_entity_manager');
       self::purgeDatabase($emSetup);
       self::purgeDatabase($emModel);
       self::loadSetupDump('/home/mgarber/dumps/Data/setup1800');
       $connModel = $emModel->getConnection();
       $connModel->query('CALL pull_from_setup()');
   }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
   public function setup()
   {
       /** @var EntityManagerInterface $em */
       $em = self::$kernel->getContainer()->get('doctrine.orm.model_entity_manager');
       $conn = $em->getConnection();
       $conn->query('TRUNCATE subevent');
       /** @noinspection SqlNoDataSourceInspection */
       $conn->query("ALTER TABLE subevent AUTO_INCREMENT=1");
   }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  'Invalid Model' at (row:10,col:3) is an unrecognized value
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
   public function test1910InvalidModel()
   {
        $parser = self::$yamlDbModelSubevent;
        $parser->parseEvents(__DIR__.'/data-1910-invalid-model.yml');
   }


   /**
    * @expectedException  \App\Common\AppParseException
    * @expectedExceptionMessage 'Invalid Style' at (row:11,col:7) is an unrecognized value
    * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
    */

   public function test1920InvalidStyle()
   {
       $parser = self::$yamlDbModelSubevent;
       $parser->parseEvents(__DIR__.'/data-1920-invalid-style.yml');
   }

   /**
    * @expectedException  \App\Common\AppParseException
    * @expectedExceptionMessage 'Invalid Substyle' at (row:12,col:9) is an unrecognized value in file:
    * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
    */
   public function test1930InvalidSubstyle()
   {
       $parser = self::$yamlDbModelSubevent;
       $parser->parseEvents(__DIR__.'/data-1930-invalid-substyle.yml');
   }

   /**
    * @expectedException  \App\Common\AppParseException
    * @expectedExceptionMessage 'Invalid Proficiency' at (row:7,col:9) is an unrecognized value in file:
    * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
    */
   public function test1940InvalidProficiency()
   {
       $parser = self::$yamlDbModelSubevent;
       $parser->parseEvents(__DIR__.'/data-1940-invalid-proficiency.yml');
   }

    /**
     * @expectedException  \App\Common\AppParseException
     * @expectedExceptionMessage 'Invalid Age' at (row:13,col:71) is an unrecognized value in file:
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
   public function test1950InvalidAge()
   {
       $parser = self::$yamlDbModelSubevent;
       $parser->parseEvents(__DIR__.'/data-1950-invalid-age.yml');
   }

    /**
     * @throws \App\Common\AppParseException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
   public function test1960ValidSequencing()
   {
       $parser = self::$yamlDbModelSubevent;
       $parser->parseEvents(__DIR__.'/model-09-subevent-sequence.yml');
       $em = self::$emModel;
       /** @var SubeventRepository $repository */
       $qb=$em->createQueryBuilder();
       $subeventCount=
       $qb->select(count('count(subevent.id)'))
           ->from('App\Entity\Model\Subevent','value')
           ->getQuery()->getSingleScalarResult();
       $eventCount =
       $qb->select(count('count(event.id)'))
           ->from('App\Entity\Model\Event','value')
           ->getQuery()->getSingleScalarResult();
       $this->assertGreaterThan($eventCount,$subeventCount);
   }

}