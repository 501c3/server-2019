<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 7:33 PM
 */

namespace App\Tests\Common;


use App\Common\AppException;
use App\Common\YamlModel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Yaml02XXModelTest  extends KernelTestCase
{
    /**
     * @var YamlModel
     */
    private $yamlModel;

    /**
     * @throws AppException
     */
    public function setUp()
    {
        $this->yamlModel = new YamlModel();
        $this->yamlModel->declareModels(__DIR__ . '/01models.yml');
        $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
        $this->yamlModel->declareValues(__DIR__.'/03values.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Unrecognized Model' at (row:30,col:1) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 124
     */
    public function test0210PersonModelUnrecognized()
    {
        $this->yamlModel->declarePersons(__DIR__.'/04model-person-0210-unrecognized.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'invalid key' at (row:9,col:5). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws \Exception
     */
    public function test0220PersonInvalidKey()
    {
        $this->yamlModel->declarePersons(__DIR__.'/04model-person-0220-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Missing type,status between rows 2 and 16
     * @expectedExceptionCode \App\Common\AppExceptionCodes::MISSING_KEYS
     * @throws \Exception
     *
     * Thrown in YamlModel:line 146
     */

    public function test0230PersonKeysMissing()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0230-keys-missing.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Type' at (row:20,col:11) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 164
     */
    public function test0240PersonInvalidType()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0240-invalid-type.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Status' at (row:19,col:13) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:Line 164
     */
    public function test0250PersonInvalidStatus()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0250-invalid-status.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Sex' at (row:17,col:9) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 173
     */
    public function test0260PersonInvalidSex()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0260-invalid-sex.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found '9-Invalid' at (row:12,col:7) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 182
     */
    public function test0270PersonInvalidYears()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0270-invalid-years.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found '6-1' at (row:10,col:7) which is not a valid range.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_RANGE
     * @throws \Exception
     *
     * Thrown in YamlModel:line 184
     */
    public function test0280PersonInvalidRange()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0280-invalid-range.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found '6-8' at (row:11,col:7) which is an overlapping range.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::OVERLAPPING_RANGE
     * @throws \Exception
     *
     */
    public function test0290PersonOverlappingRange()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0290-overlapping-range.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Missing 16,17,18 between rows 10 and 15
     * @expectedExceptionCode \App\Common\AppExceptionCodes::MISSING_KEYS
     * @throws \Exception
     */
    public function test0300PersonMissingRange()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0300-missing-range.yml');
    }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Age' at (row:12,col:13) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     */
    public function test0310PersonInvalidAge()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0310-invalid-age.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Proficiency' at (row:6,col:7) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     */
    public function test0320PersonInvalidProficiency()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0320-invalid-proficiency.yml');
    }


    public function test0330PersonAgeStruct()
    {
        //TODO: Verify structure
        $this->yamlModel->declarePersons(__DIR__ . '/04model-person-0330-person-age-struct.yml');
    }

}