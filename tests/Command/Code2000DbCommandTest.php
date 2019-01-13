<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/19/18
 * Time: 2:47 PM
 */

namespace App\Tests\Command;


use App\Command\DbBuildSetupCommand;
use App\Command\DbLoadCommand;
use App\Entity\Setup\Model;
use App\Kernel;
use App\Repository\Model\ModelRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Dotenv\Dotenv;
use /** @noinspection PhpUnusedAliasInspection */
    App\Command\DbTruncateCommand;

class Code2000DbCommandTest extends KernelTestCase
{
    const DUMP_SETUP = '/home/mgarber/dumps/Data/setup2000';

    const TEST_BUILDS = false;

    /** @var Kernel */
    protected static $kernel;

    /** @var Application application */
    private static $application;

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
        self::$kernel = self::bootKernel();
        self::$application = new Application(self::$kernel);
        self::$application->add(new DbTruncateCommand(self::$kernel));
        self::$application->add(new DbLoadCommand(self::$kernel));
        self::$application->add(new DbBuildSetupCommand(self::$kernel));
        self::purgeDatabase('setup',ORMPurger::PURGE_MODE_TRUNCATE);
    }


    /**
     * @param string $dbname
     * @param int $purgeMode
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function purgeDatabase(string $dbname, int $purgeMode)
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get("doctrine.orm.$dbname"."_entity_manager");
        /** @var Connection $conn */
        $conn = $em->getConnection();
        $conn->query('SET foreign_key_checks = 0');
        $purger = new ORMPurger($em);
        $purger->setPurgeMode($purgeMode);
        $purger->purge();
        $conn->query('SET foreign_key_checks = 1');
        $conn->query('UNLOCK TABLES');
    }

    /**
     * @param string $dbname
     * @param string $dumpFileOrDirectory
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function loadDump(string $dbname, string $dumpFileOrDirectory)
    {

        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get("doctrine.orm.$dbname"."_entity_manager");
        $conn = $em->getConnection();
        $dumpFiles = self::findDumpFiles($dumpFileOrDirectory);
        foreach($dumpFiles as $file) {
            $sql = file_get_contents(($file));
            $conn->query($sql);
        }
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

    public function test2110SetupMissingMaster()
    {
        $application = new Application(self::$kernel);
        $application->add(new DbBuildSetupCommand(self::$kernel));
        $command = $application->find('db:build:setup');
        $parameters=['command'=>$command->getName(),'masterFile'=>__DIR__.'/data-2110-master-non-existent.yml'];
        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
        $output = $commandTester->getDisplay();
        $this->assertContains(' Missing master file:',$output);
    }


    public function test2120SetupMasterFileNotFound()
    {
        $application = new Application(self::$kernel);
        $application->add(new DbBuildSetupCommand(self::$kernel));
        $command = $application->find('db:build:setup');
        $parameters=['command'=>$command->getName(),'masterFile'=>__DIR__.'/data-2120-file-not-found.yml'];
        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
        $output = $commandTester->getDisplay();
        $this->assertContains(' Found event-values but expected [models,domains,values,model-values,',$output);
    }

    public function test2130SetupMasterMissingComponent()
    {
        $application = new Application(self::$kernel);
        $application->add(new DbBuildSetupCommand(self::$kernel));
        $command = $application->find('db:build:setup');
        $parameters=['command'=>$command->getName(),'masterFile'=>__DIR__.'/data-2130-component-not-found.yml'];
        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
        $output = $commandTester->getDisplay();
        $this->assertContains('Found event-values but expected [models,domains,values,model-values,',$output);
    }


    /**
     * @return string
     */
    private function run2100DbSetupLoad()
    {
        $command = self::$application->find('db:load');
        $commandTester = new CommandTester($command);
        $parameters=['command'=>$command->getName(),'dbname'=>'setup','dump'=>self::DUMP_SETUP];
        $commandTester->execute($parameters);
        $output = $commandTester->getDisplay();
        return $output;
    }


    /**
     * @return string
     */
    private function run2100DbSetupTruncate()
    {
        /** @var EntityManagerInterface $em */
        $command = self::$application->find('db:truncate');
        $commandTester = new CommandTester($command);
        $parameters  = ['command'=>$command->getName(),'dbname'=>'setup'];
        $commandTester->execute($parameters);
        $output = $commandTester->getDisplay();
        return $output;
    }


    private function run2100DbSetupBuild()
    {

        $application = new Application(self::$kernel);
        $application->add(new DbBuildSetupCommand(self::$kernel));
        $command = $application->find('db:build:setup');
        $parameters=['command'=>$command->getName(),'masterFile'=> __DIR__ . '/data-2140-master-valid.yml'];
        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
        $output=$commandTester->getDisplay();
        return $output;

    }


    public function test2150SetupValid()
    {
        $em = self::$kernel->getContainer()->get("doctrine.orm.setup_entity_manager");
        /** @var ModelRepository $repositoryModel */
        $repositoryModel=$em->getRepository(Model::class);

        $modelsBeforeLoad = $repositoryModel->findAll();
        $this->assertEquals(0,count($modelsBeforeLoad));

        $this->run2100DbSetupLoad();
        $modelsAfterLoad = $repositoryModel->findAll();
        $this->assertEquals(3,count($modelsAfterLoad));

        $this->run2100DbSetupTruncate();
        $modelsAfterTruncate = $repositoryModel->findAll();
        $this->assertEquals(0,count($modelsAfterTruncate));

        if(self::TEST_BUILDS) {
            $this->run2100DbSetupBuild();

        }
    }
}