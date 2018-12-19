<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/9/18
 * Time: 2:49 PM
 */

namespace App\Tests\Common;


use App\Common\YamlDbSetupEvent;
use App\Entity\Setup\Event;
use App\Entity\Setup\Model;
use App\Kernel;
use App\Repository\Setup\EventRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class Code1600SetupEventTest extends KernelTestCase
{
    /** @var  Kernel */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__ . '/../../.env');
    }

    /**
     * @return mixed
     */
    private function getEntityManager(): EntityManagerInterface
    {
        return self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
    }/** @noinspection PhpUnusedParameterInspection */


    /**
     * @param array $excluded
     * @throws \Doctrine\DBAL\DBALException
     * @noinspection PhpUnusedParameterInspection
     */
    protected function purge(array $excluded=[])
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $purger = new ORMPurger($em,$excluded);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $conn = $purger->getObjectManager()->getConnection();
        $conn->query('SET FOREIGN_KEY_CHECKS=0');
        $purger->purge();
        $conn->query('SET FOREIGN_KEY_CHECKS=1');
        $conn->query('UNLOCK TABLES');
    }

    /**
     * @return YamlDbSetupEvent
     */
    protected function getYamlDbSetupEvent()
    {
        $em = $this->getEntityManager();
        return new YamlDbSetupEvent($em);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setUp()
    {
        self::$kernel = self::bootKernel();
        $this->purge();
        $this->loadModelsDomainsValuesTeams();
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadModelsDomainsValuesTeams()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $dump = file_get_contents(__DIR__ . '/Dump20181216.sql');
        //$dump = file_get_contents(__DIR__ . '/setup-06-teams-data.sql');
        $conn->query('set GLOBAL net_buffer_length=3000000');
        $conn->query('SET foreign_key_checks = 0');
        $conn->query($dump);
        $conn->query('SET foreign_key_checks = 1');
        $conn->query('UNLOCK TABLES');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage 'Unrecognized Model' at (row:1,col:1) is an unrecognized value in file:
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     */
    public function test1610InvalidModel()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1610-unrecognized-model.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'invalid_key' at (row:2,col:3) but expected [proficiency|age|sex|type|status]
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1620InvalidKey()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1620-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage 'Found 'invalid_key' at (row:4,col:7) but expected [tag|style]
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1630InvalidValue()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1630-invalid-value.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'invalid_key' at (row:4,col:7) but expected [tag|style]
     * @expectedExceptionCode  \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1640InvalidKey()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1640-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'Invalid Value' at (row:6,col:9) but expected [International|American]
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1660InvalidValue()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1660-invalid-value.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'invalid_key' at (row:7,col:11) but expected [disposition|substyle]
     * @expectedExceptionCode App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1670InvalidKey()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1670-invalid-keys.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'invalid-disposition' at (row:7,col:24) but expected [multiple-events|single-event]
     * @expectedExceptionCode App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1680InvalidDisposition()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1680-invalid-disposition.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  Found 'Invalid Substyle' at (row:9,col:13) but expected [Rhythm|Smooth|Latin|Standard]
     * @expectedExceptionCode App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1690InvalidSubstyles()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1690-invalid-substyles.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'Cha Cha' at (row:9,col:22) but expected [left square bracket]
     * @expectedExceptionCode App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1700ArrayExpected()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1700-array-expected.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  Found 'Cha Cha' at (row:9,col:23) but expected [square left bracket]
     * @expectedExceptionCode App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \Exception
     */
    public function test1710ScalarExpected()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1710-scalar-expected.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  'Invalid Dance' at (row:9,col:23) is an unrecognized value in file:
     * @expectedExceptionCode App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     */
    public function test1720InvalidDanceMulti()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1720-invalid-dance-multi.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage 'Invalid Dance' at (row:9,col:22) is an unrecognized value in file:
     * @expectedExceptionCode App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     */

    public function test1730InvalidDanceSingle()
    {
        $setup = $this->getYamlDbSetupEvent();
        $setup->parseEvents(__DIR__ . '/data-1730-invalid-dance-single.yml');
    }


    /**
     * @param $yamlPathFile
     * @throws \Exception
     */
    private function loadAndIterateThroughDatabase($yamlPathFile)
    {

        $setup = $this->getYamlDbSetupEvent();
        $expected = $setup->parseEvents($yamlPathFile);
        /** @var EventRepository $repository */
        $repository = $this->getEntityManager()->getRepository(Event::class);
        $results = $repository->findAll();
        $this->assertEquals(10,count($results));
        $entityManager = $this->getEntityManager();
        /** @var Model $model */
        $model = $entityManager->getRepository(Model::class)->find(1);
        $modelName = $model->getName();
        $actual = $repository->fetchQuickSearch($model);
        foreach($expected[$modelName] as $type=>$statusList) {
            $this->assertArrayHasKey($type,$actual);
            foreach($statusList as $status=>$sexList) {
                $this->assertArrayHasKey($status,$actual[$type]);
                foreach($sexList as $sex=>$ageList) {
                    $this->assertArrayHasKey($sex,$actual[$type][$status]);
                    foreach($ageList as $age=>$proficiencyList) {
                        $this->assertArrayHasKey($age,$actual[$type][$status][$sex]);
                        foreach($proficiencyList as $proficiency=>$styleList) {
                            $this->assertArrayHasKey($proficiency,$actual[$type][$status][$sex][$age]);
                            foreach($styleList as $style=>$eventList) {
                                $this->assertArrayHasKey($style,$actual[$type][$status][$sex][$age][$proficiency]);
                                $expEvents=$expected[$modelName][$type][$status][$sex][$age][$proficiency][$style];
                                $actEvents=$actual[$type][$status][$sex][$age][$proficiency][$style];
                                $this->assertEquals(count($expEvents),count($actEvents));
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * @throws \Exception
     */
    public function test1740ValidEventsPartial()
    {
        $this->loadAndIterateThroughDatabase(__DIR__.'/data-1740-valid-events-partial.yml');
    }


    /**
     * @throws \Exception
     */
    public function test1750ValidEventsAll()
    {
        $this->loadAndIterateThroughDatabase(__DIR__.'/data-1750-valid-events-all.yml');
    }



}