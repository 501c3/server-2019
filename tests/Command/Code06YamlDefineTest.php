<?php

namespace App\Tests;

use App\Command\YamlDefineCommand;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class Code06YamlDefineTest extends KernelTestCase
{

    private function commandTestBuild($masterfile)
    {
        $application = new Application();
        $command = new YamlDefineCommand();
        $application->add($command);
        $executionItem = [
            'command'=>$command->getName(),
            'master-file'=>$masterfile
        ];
        $commandTester = new CommandTester($command);
        $commandTester->execute($executionItem);
        $output =  $commandTester->getDisplay();
        return $output;
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage ../../tests/Command/master-file was not found.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FILE_NOT_FOUND
     */
    public function test0700MasterFileNotFound()
    {
        $this->commandTestBuild('../../tests/Command/master-file');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage [persons,teams] was not found in ../../tests/Command/data-06-master-0010-missing.yml.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FILE_NOT_FOUND
     */
    public function test0710MissingKeys()
    {
        $this->commandTestBuild('../../tests/Command/data-06-master-0010-missing.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  [values] was not found in ../../tests/Command/data-06-master-0020-file-not-found.yml.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FILE_NOT_FOUND
     */
    public function test0720FileNotFound()
    {
        $this->commandTestBuild('../../tests/Command/data-06-master-0020-file-not-found.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage tests/Common/missing.yml was not found.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::FILE_NOT_FOUND
     */
    public function test0730ComponentNotFound()
    {
        $this->commandTestBuild('../../tests/Command/data-06-master-0030-component-not-found.yml');
    }

    public function testComplete()
    {
        $this->commandTestBuild('../../tests/Command/master-file.yml');
    }

}
