<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/27/18
 * Time: 7:50 PM
 */

namespace App\Tests\Common;


use App\Common\YamlModel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Code04YamlModelTest extends KernelTestCase
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
       $this->yamlModel->declareModelValues(__DIR__.'/data-01-values-0000-model.yml');
       $this->yamlModel->declarePersons(__DIR__.'/data-02-persons.yml');
       $this->yamlModel->declareTeams(__DIR__.'/data-03-teams.yml');
   }
   /**
    * @expectedException  \App\Common\AppException
    * @expectedExceptionMessage 'Invalid Model' at (row:1,col:1) which is not a recognized value.
    * @empectedMessageCode \App\Common\AppExceptionCode::UNRECOGNIZED_VALUE
    */

   public function test0380EventsInvalidModel()
   {
       $result=$this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0380-invalid-model.yml');
   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Key' at (row:91,col:3). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode  \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0390EventsInvalidKey()
    {
        $result=$this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0390-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Missing sex between rows 2 and 93
     * @expectedExceptionCode \App\Common\AppExceptionCodes::MISSING_KEYS
     */
    public function test0400EventsMissingKey()
    {
        $result=$this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0400-missing-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Proficiency' at (row:3,col:5) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0410EventsInvalidProficiency()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0410-invalid-proficiency.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid key' at (row:5,col:7). Expected [tag, style].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */

    public function test0420EventsInvalidKey()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0420-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Tag' at (row:4,col:12) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     *
     */
    public function test0430EventsInvalidTag()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0430-invalid-tag.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Style' at (row:6,col:9) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0440EventsInvalidStyle()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0440-invalid-style.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid key' at (row:8,col:11). Expected [disposition, substyle].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0450EventsInvalidKey()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0450-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid-disposition' at (row:7,col:24). Expected [multiple-events, single-event].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0460EventsInvalidDisposition()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0460-invalid-disposition.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Substyle' at (row:9,col:13) which is not a recognized value
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0470EventsInvalidSubstyle()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0470-invalid-substyle.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Cha Cha' at (row:9,col:22) but expected an array ie dash '-'.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::ARRAY_EXPECTED
     */
    public function test0480EventsDoubleBrackets()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0480-double-brackets.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found array near Cha Cha (row:9,col:23) but expected scaler.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::SCALER_EXPECTED
     */

    public function test0490EventsSingleBracket()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0490-single-brackets.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Dance' at (row:498,col:60) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0500EventsInvalidDance()
    {
        $this->yamlModel->declareEvents(__DIR__ . '/data-04-events-0500-invalid-dance.yml');
    }



}