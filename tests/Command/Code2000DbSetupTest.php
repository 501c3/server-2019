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
use App\Kernel;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Dotenv\Dotenv;
use /** @noinspection PhpUnusedAliasInspection */
    App\Command\DbTruncateCommand;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Code2000DbSetupTest extends KernelTestCase
{
    const DUMP = '/home/mgarber/dumps/Data/setup';

    /** @var Kernel */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
         self::$kernel = self::bootKernel();
         self::purgeDatabase();
    }

    private static function purgeDatabase()
    {
        $em = self::$kernel->getContainer()->get("doctrine.orm.setup_entity_manager");
        $conn = $em->getConnection();
        $conn->query('SET foreign_key_checks = 0');
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
        $conn->query('SET foreign_key_checks = 1');
        $conn->query('UNLOCK TABLES');
    }

    /**
     * @param string $dumpDirectory
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function loadDump(string $dumpDirectory)
    {

        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get("doctrine.orm.setup_entity_manager");
        $conn = $em->getConnection();
        $dumpFiles = self::findDumpFiles($dumpDirectory);
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



    private function initializeCommand($class,string $initializationCall,array $executionItem)
    {
        $application = new Application(self::$kernel);
        $application->add(new $class(self::$kernel));
        $command = $application->find($initializationCall);
        $commandTester = new CommandTester($command);
        $executionItem['command'] = $command->getName();
        $commandTester->execute($executionItem);
        $output = $commandTester->getDisplay();
        return trim($output);
    }


    public function test2100DbTruncate()
    {
        $output = $this->initializeCommand(
            DbTruncateCommand::class,
            'db:truncate',
            ['dbname'=>'setup']);
        $this->assertSame('[OK] Database setup has been successfully truncated.',trim($output));
    }

    public function test2200DbLoad()
    {
        $output = $this->initializeCommand(
            DbLoadCommand::class,
            'db:load',
            ['dbname'=>'setup','dump'=>self::DUMP]);
        $strings = explode('\n',$output);
        $this->assertStringStartsWith( "Commencing at", $strings[0]);
        $this->assertStringStartsWith("Completed at", $strings[2]);
    }




    private function buildExecute(string $fileName)
    {
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        $application = new Application(self::$kernel);
        $application->add(new DbBuildSetupCommand(self::$kernel));
        $command = $application->find('db:build:setup');
        $parameters=['command'=>$command->getName(),'masterFile'=>$fileName];
        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
        $output=$commandTester->getDisplay();
        return trim($output);
    }


    public function test2300DbBuildSetup()
    {
//        $output = $this->initializeCommand(
//            DbTruncateCommand::class,
//            'db:truncate',
//            ['dbname'=>'setup']);
//        /** @var EntityManagerInterface $em */
        $this->buildExecute(__DIR__.'/data-2010-master-valid.yml');
    }
}