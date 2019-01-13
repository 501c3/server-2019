<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/21/18
 * Time: 9:15 PM
 */

namespace App\Tests\Common;


use App\Common\AppBuildException;
use App\Common\AppExceptionCodes;
use App\Common\YamlDbSetupEventTeam;
use App\Entity\Setup\Event;
use App\Kernel;
use App\Repository\Setup\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class Code1800SetupEventTeamTest extends KernelTestCase
{
  const ALL_AGES=
      ['Y00-00','Y01-04','Y05-05','Y06-06','Y07-07','Y08-08','Y09-09','Y10-10','Y11-11','Y12-12', 'Y13-13','Y14-14',
      'Y15-15','Y16-16','Y17-17','Y18-18','Y19-34','Y35-44','Y45-49','Y50-54','Y55-64','Y65-74','Y75-99','Under 6',
      'Under 8','Under 12','Junior 12-16','Adult 16-50','Senior 50','Preteen 1','Preteen 2','Junior 1','Junior 2',
      'Youth','Adult','Senior 1','Senior 2','Senior 3','Senior 4','Senior 5','Senior','Baby','Juvenile'];

  const ALL_PROFICIENCIES=
      ['Social','Newcomer','Pre Bronze','Intermediate Bronze','Full Bronze','Open Bronze','Bronze',
       'Pre Silver','Intermediate Silver','Full Silver','Open Silver','Silver',
       'Pre Gold','Intermediate Gold','Full Gold','Open Gold','Gold','Novice','Pre Championship',
       'Gold Star 1','Championship','Gold Star 2','Rising Star','Professional'];

  const QUALIFICATIONS_NOT_FOUND = <<<HEREDOC
Qualifications of Team(type:%s, status: %s, sex: %s, age:%s, proficiency:%s) was not found for 
Event(model:%s, type:%s, status:%s, sex:%s, age:%s, proficiency:%s).";
HEREDOC;
    /** @var Kernel */
    protected static $kernel;

    /** @var  YamlDbSetupEventTeam*/
    private $setup;

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__ . '/../../.env');
        self::$kernel = self::bootKernel();
        self::purge();
        self::loadSetupDump('/home/mgarber/dumps/Data/setup1600');
    }


    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function purge()
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $conn = $purger->getObjectManager()->getConnection();
        $conn->query('SET FOREIGN_KEY_CHECKS=0');
        $conn->query('TRUNCATE event');
        $conn->query('TRUNCATE event_has_value');
        $conn->query('TRUNCATE event_has_team_class');
        $conn->query('SET FOREIGN_KEY_CHECKS=1');
        $conn->query('UNLOCK TABLES');
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


    /**
     * @return YamlDbSetupEventTeam
     */
    protected function getYamlDbSetupEventTeam()
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        return new YamlDbSetupEventTeam($em);
    }


    public function setUp()
    {
        $this->setup = $this->getYamlDbSetupEventTeam();
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage Found 'Invalid Model' at (row:1,col:1) but expected [ISTD Medal Exams-2019
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \App\Common\AppParseException
     */
    public function test1810InvalidModel()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1810-invalid-model.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  Found 'invalid_key' at (row:2,col:5) but expected [proficiency|age]
     * @expectedExceptionCode  \App\Common\AppExceptionCodes::FOUND_BUT_EXPECTED
     * @throws \App\Common\AppParseException
     */
    public function test1820InvalidDomainKey()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1820-invalid-domain-key.yml');
    }

    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  'Invalid Value Event' at (row:3,col:7) is an unrecognized value in file:
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \App\Common\AppParseException
     */

    public function test1830InvalidValueEvent()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1830-invalid-value-event.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  'Invalid Value Team' at (row:4,col:9) is an unrecognized
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \App\Common\AppParseException
     */
    public function test1840InvalidValueTeam()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1840-invalid-value-team.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  Expected structure following 'Open Silver' at (row:145,col:7)
     * @expectedExceptionCode  \App\Common\AppExceptionCodes::EXPECTED_STRUCTURE
     * @throws \App\Common\AppParseException
     */
    public function test1850InvalidExpectStructure()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1850-invalid-expect_structure.yml');
    }


    /**
     * @expectedException \App\Common\AppParseException
     * @expectedExceptionMessage  'Invalid Value' at (row:209,col:18) is an unrecognized value in file:
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     *
     * @throws \App\Common\AppParseException
     */
    public function test1860InvalidErrorDelayed()
    {
        $this->setup->parseEventsTeams(__DIR__ . '/data-1860-invalid-error-delayed.yml');
    }


    /**
     * @throws \App\Common\AppParseException
     */
    public function test1870ValidEventTeam()
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        $expectedRelations = yaml_parse_file(__DIR__.'/setup-08-event-team.yml');
        $this->setup->parseEventsTeams(__DIR__.'/setup-08-event-team.yml');
        /** @var EventRepository $repository */
        $repository = $em->getRepository(Event::class);
        $actual = $repository->fetchEligibility();
        foreach($expectedRelations as $modelName => $groupingList) {
            foreach($groupingList as $grouping) {
                $types = $grouping['type'];
                $statii= $grouping['status'];
                $sexes = $grouping['sex'];
                $ages  = $grouping['age'];
                $proficiencies = $grouping['proficiency'];
                foreach($types as $expectedEventType => $expectedTeamTypes) {
                    foreach($statii as $expectedEventStatus => $expectedTeamStatii) {
                        foreach($sexes as $expectedEventSex => $expectedTeamSexes) {
                            foreach($ages as $expectedEventAge => $expectedTeamAges) {
                                foreach($proficiencies as $expectedEventProficiency => $expectedTeamProficiencies) {
                                    $this->compareActualExpected($actual,$modelName,
                                        $expectedEventType, $expectedTeamTypes,
                                        $expectedEventStatus, $expectedTeamStatii,
                                        $expectedEventSex, $expectedTeamSexes,
                                        $expectedEventAge, $expectedTeamAges,
                                        $expectedEventProficiency, $expectedTeamProficiencies);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $actual
     * @param string $modelName
     * @param $expectedEventType
     * @param $expectedTeamTypes
     * @param $expectedEventStatus
     * @param $expectedTeamStatii
     * @param $expectedEventSex
     * @param $expectedTeamSexes
     * @param $expectedEventAge
     * @param $expectedTeamAges
     * @param $expectedEventProficiency
     * @param $expectedTeamProficiencies
     * @throws AppBuildException
     */

    private function compareActualExpected(array $actual, string $modelName,
                                           $expectedEventType, $expectedTeamTypes,
                                           $expectedEventStatus, $expectedTeamStatii,
                                           $expectedEventSex, $expectedTeamSexes,
                                           $expectedEventAge, $expectedTeamAges,
                                           $expectedEventProficiency, $expectedTeamProficiencies)
    {
        /** @var ArrayCollection $actualEventCollection */
        if(!isset($actual[$modelName][$expectedEventType][$expectedEventStatus]
                [$expectedEventSex][$expectedEventAge][$expectedEventProficiency])){
            $index=[$modelName,$expectedEventType,$expectedEventStatus,$expectedEventSex,
                    $expectedEventAge,$expectedEventProficiency];
            throw new AppBuildException(AppExceptionCodes::EXPECTED_STRUCTURE,
                    [__FILE__,__LINE__,'$actual',$index]);
        }
        $actualEventCollection = $actual[$modelName][$expectedEventType][$expectedEventStatus]
                                        [$expectedEventSex][$expectedEventAge][$expectedEventProficiency];

        /** @var Event $eventCurrent */
        $eventCurrent = $actualEventCollection->first();
        while($eventCurrent) {
            $actualDescribe = $eventCurrent->getDescribe();
            $expectedDescribe = ['type'=>$expectedEventType,'status'=>$expectedEventStatus,
                                 'sex'=>$expectedEventSex,'age'=>$expectedEventAge,
                                 'proficiency'=>$expectedEventProficiency];
            $this->assertArraySubset($expectedDescribe,$actualDescribe);
            /** @var ArrayCollection $teamClassCollection */
            $teamClassCollection = $eventCurrent->getTeamClass();
            foreach($expectedTeamTypes as $expectedTeamType) {
                foreach($expectedTeamStatii as $expectedTeamStatus) {
                    foreach($expectedTeamSexes as $expectedTeamSex) {
                        foreach($expectedTeamAges as $expectedTeamAge) {
                            foreach($expectedTeamProficiencies as $expectedTeamProficiency) {
                                $expectedDescribe=['type'=>$expectedTeamType,
                                                 'status'=>$expectedTeamStatus,
                                                 'sex'=>$expectedTeamSex,
                                                 'age'=>$expectedTeamAge,
                                                 'proficiency'=>$expectedTeamProficiency];
                                $this->assertTrue(
                                    $teamClassCollection->exists(/**
                                     * @param $key
                                     * @param $teamClass
                                     * @return bool
                                     */
                                        function(/** @noinspection PhpUnusedParameterInspection */
                                            $key, $teamClass) use ($expectedDescribe) {
                                            /** @noinspection PhpUndefinedMethodInspection */
                                            $actualDescribe=$teamClass->getDescribe();
                                        return $actualDescribe == $expectedDescribe;
                                    }),sprintf(self::QUALIFICATIONS_NOT_FOUND,
                                                $expectedTeamType,
                                                $expectedTeamStatus,
                                                $expectedTeamSex,
                                                $expectedTeamAge,
                                                $expectedTeamProficiency,
                                                $modelName,
                                                $expectedEventType,
                                                $expectedEventStatus,
                                                $expectedEventSex,
                                                $expectedEventAge,
                                                $expectedEventProficiency));
                            }
                        }

                    }
                }
            }
            $eventCurrent = $actualEventCollection->next();
        }

    }
}