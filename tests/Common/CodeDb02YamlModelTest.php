<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 7:33 PM
 */

namespace App\Tests\Common;


use App\Common\AppException;
use App\Common\YamlDbModel;
use App\Entity\Models\Person;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class CodeDb02YamlModelTest  extends KernelTestCase
{

    /** @var \App\Kernel */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
    }



    /** @var YamlDbModel */
    private $yamlDbModel;


    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function clearDb()
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::$kernel->getContainer()->get('doctrine.orm.models_entity_manager');
        $this->yamlDbModel = new YamlDbModel($entityManager);
        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $connection = $purger->getObjectManager()->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $purger->purge();
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->yamlDbModel = new YamlDbModel($entityManager);
    }


    /**
     * @throws AppException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setUp()
    {
        self::$kernel = self::bootKernel();
        $this->clearDb();
        $this->yamlDbModel->declareModels(__DIR__ . '/models.yml');
        $this->yamlDbModel->declareDomains(__DIR__ . '/domains.yml');
        $this->yamlDbModel->declareValues(__DIR__ . '/values.yml');
    }



    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'invalid_key' at (row:3,col:3). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws \Exception
     */
    public function test0220PersonInvalidKey()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0220-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Missing sex,age between rows 1 and 3.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::MISSING_KEYS
     * @throws \Exception
     *
     * Thrown in YamlModel:line 146
     */

    public function test0230PersonKeysMissing()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0230-keys-missing.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Type' at (row:1,col:9) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 164
     */
    public function test0240PersonInvalidType()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0240-invalid-type.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Status' at (row:2,col:11) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:Line 164
     */
    public function test0250PersonInvalidStatus()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0250-invalid-status.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Sex' at (row:5,col:7) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 173
     */
    public function test0260PersonInvalidSex()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0260-invalid-sex.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found '16-Invalid' at (row:6,col:8) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 182
     */
    public function test0270PersonInvalidYears()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0270-invalid-years.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found '16-13' at (row:6,col:8) which is not a valid range.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_RANGE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 184
     */
    public function test0280PersonInvalidRange()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0280-invalid-range.yml');
    }



    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Proficiency' at (row:23,col:5) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     */
    public function test0290PersonInvalidProficiency()
    {
        $this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0320-invalid-proficiency.yml');
    }



    public function test0300PersonVerify()
    {
        $result=$this->yamlDbModel->declarePersons(__DIR__ . '/data-02-persons-0340-verify.yml');
        /** @var Person $person */
        $person = $result['Professional']['Teacher']['Male'][20]['Professional'];
        $actual = $person->getDescription();
        $expected = [
        'type' =>"Professional",
        'status' =>"Teacher",
        'sex' =>"Male",
        'years' => 20,
        'proficiency' =>"Professional"
        ];
        $this->assertEquals($expected,$actual);
    }

    public function test0310PersonQuickSearch()
    {
        $expect = $this->yamlDbModel->declarePersons(__DIR__ . '/persons.yml');
        $actual = $this->yamlDbModel->fetchQuickSearch(Person::class);
        foreach($expect as $type=>$statusList) {
            $this->assertArrayHasKey($type,$actual);
            foreach($statusList as $status=>$sexList) {
                $this->assertArrayHasKey($status,$actual[$type]);
                foreach($sexList as $sex=> $yearList) {
                    $this->assertArrayHasKey($sex,$actual[$type][$status]);
                    foreach($yearList as $year=>$proficiencyList) {
                        $this->assertArrayHasKey($year,$actual[$type][$status][$sex]);
                        /**
                         * @var string $proficiency
                         * @var  Person $person
                         */
                        foreach($proficiencyList as $proficiency => $person) {
                            $this->assertArrayHasKey($proficiency, $actual[$type][$status][$sex][$year]);
                            $description = $person->getDescription();
                            $expected = ['type'=>$type,
                                         'status'=>$status,
                                         'sex'=>$sex,
                                         'years'=>$year,
                                         'proficiency'=>$proficiency];
                            $this->assertEquals($expected,$description);
                        }
                    }
                }
            }
        }
    }
}