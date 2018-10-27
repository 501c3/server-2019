<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/27/18
 * Time: 10:47 AM
 */

namespace App\Tests\Common;

use App\Common\YamlModel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
;

class Code03YamlModelTest extends KernelTestCase
{
    /**
     * @var YamlModel
     */
    private $yamlModel;

    /**
     * @throws \App\Common\AppException
     */
    public function setUp()
    {
        $this->yamlModel = new YamlModel();
        $this->yamlModel->declareModels(__DIR__ . '/data-01-models.yml');
        $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
        $this->yamlModel->declareValues(__DIR__ . '/data-01-values.yml');
        $this->yamlModel->declarePersons(__DIR__.'/data-02-persons.yml');
    }

    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid_key' at (row:16,col:5). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws \App\Common\AppException
     */
    public function test0350TeamsInvalidKey()
    {
        $this->yamlModel->declareTeams(__DIR__ . '/data-03-teams-0350-invalid-key.yml');
    }


    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Missing sex between rows 101 and 115
     # @expectedExceptionMessage \App\Common\AppExceptionCode::MISSING_KEYS
     * @throws \App\Common\AppException
     */
    public function test0360TeamsMissingKey()
    {
        $this->yamlModel->declareTeams(__DIR__ . '/data-03-teams-0360-missing-key.yml');
    }

    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Value' at (row:18,col:7) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     * @throws \App\Common\AppException
     */
    public function test0370TeamsInvalidValue()
    {
        $this->yamlModel->declareTeams(__DIR__ . '/data-03-teams-0370-invalid-value.yml');
    }

}