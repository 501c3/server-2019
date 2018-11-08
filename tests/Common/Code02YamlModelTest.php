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

class Code02YamlModelTest  extends KernelTestCase
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
        $this->yamlModel->declareModels(__DIR__ . '/models.yml');
        $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
        $this->yamlModel->declareValues(__DIR__ . '/values.yml');
        $this->yamlModel->declarePersons(__DIR__.'/persons.yml');
    }



    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'invalid_key' at (row:3,col:3). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws \Exception
     */
    public function test0220PersonInvalidKey()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0220-invalid-key.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0230-keys-missing.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0240-invalid-type.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0250-invalid-status.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0260-invalid-sex.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0270-invalid-years.yml');
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
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0280-invalid-range.yml');
    }



    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Proficiency' at (row:23,col:5) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \Exception
     *
     */
    public function test0320PersonInvalidProficiency()
    {
        $this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0320-invalid-proficiency.yml');
    }


    /**
     * @throws AppException
     */

    public function test0340PersonVerify()
    {
        $result=$this->yamlModel->declarePersons(__DIR__ . '/data-02-persons-0340-verify.yml');
        $actual = $result['Professional']['Teacher']['Male'][20]['Professional'];
        $expected = [
        'type' =>"Professional",
        'status' =>"Teacher",
        'sex' =>"Male",
        'years' => 20,
        'proficiency' =>"Professional"
        ];
        $this->assertEquals($expected,$actual);
    }

}