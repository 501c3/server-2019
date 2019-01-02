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


use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Dotenv\Dotenv;
use /** @noinspection PhpUnusedAliasInspection */
    App\Command\DbTruncateCommand;

class Code2000DbCommandTest extends KernelTestCase
{
    const DUMP = '/home/mgarber/dumps/Data/setup';

    /** @var Kernel */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
        self::$kernel = self::bootKernel();
    }

    private function commandImplement($class,$call,$dbname,$filePath = null)
    {
        $kernel = self::$kernel;
        $application = new Application($kernel);
        $application->add(new $class($kernel));
        $command = $application->find($call);
        $commandTester = new CommandTester($command);
        $executionItem = ['command'=>$command->getName(),'dbname'=>$dbname];
        if(!is_null($filePath)) {
            $executionItem['dump'] = $filePath;
        }
        $commandTester->execute($executionItem);
        $output = $commandTester->getDisplay();
        return $output;
    }

    private function getEntityManager(string $name) : EntityManagerInterface
    {
        $emName = 'doctrine.orm.'.$name.'_entity_manager';
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get($emName);
        return $em;
    }




    public function testDbTruncate()
    {
        $output = $this->commandImplement(
            DbTruncateCommand::class,
            'db:truncate',
            'setup');
        $this->assertSame('[OK] Database setup has been successfully truncated.',trim($output));
    }

    public function testDbLoad()
    {
        $output = $this->commandImplement(
            DbTruncateCommand::class,
            'db:load',
            'setup',
            self::DUMP);
        $strings = explode('\n',$output);
        $this->assertStringStartsWith( "Commencing at", $strings[0]);
        $this->assertStringStartsWith("Completed at", $strings[2]);
    }
}