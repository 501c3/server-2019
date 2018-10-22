<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 12:37 PM
 */

namespace App\Tests\Common;

use App\Common\YamlModel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Yaml01XXModelTest extends KernelTestCase
{
    const TEST_VALUE
        = [
            'International' => ['abbr' => "I"],
            'American' =>['abbr' =>"A"],
            'Country' =>['abbr' =>"C"],
            'Fun Events' =>['abbr' => "F"]
        ];


    /**
     * @var YamlModel
     */
    private  $yamlModel;

   public function setUp()
   {
      $this->yamlModel = new YamlModel();
   }

   public function test0110Model()
   {
       $this->yamlModel->declareModels(__DIR__ . '/01models.yml');
       $structure = $this->yamlModel->getStructure();
       $count=count($structure);
       $this->assertEquals(3,$count);
   }

   public function test0120Domain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
       $structure = $this->yamlModel->fetchDomains();
       $count=count($structure);
       $this->assertEquals(11,$count);
   }

   /**
    * @expectedException \App\Common\AppException
    * @expectedExceptionMessage Found 'bad_domain' at (row:6,col:1). Expected [style, substyle, status, type, grouping, sex, proficiency, age, tag, dance, genre].
    * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
    */
   public function test0130ValuesBadDomain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
       $this->yamlModel->declareValues(__DIR__.'/03values-0130-bad-domain.yml');

   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_key' at (row:10,col:13). Expected [abbr, note, domain].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
   public function test0140ValueBadKey()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
       $this->yamlModel->declareValues(__DIR__.'/03values-0140-bad-key.yml');
   }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_style'. Expected [style, substyle, status, type, grouping, sex, proficiency, age, tag, dance, genre].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_PARAMETER
     */
   public function test0150ValueBadDomain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
       $this->yamlModel->declareValues(__DIR__.'/03values.yml');
       $this->yamlModel->fetchValues('bad_style');
   }


    /**
     * @throws \App\Common\AppException
     */
    public function test0160ValueConfirm()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/02domains.yml');
       $this->yamlModel->declareValues(__DIR__.'/03values.yml');
       $values=$this->yamlModel->fetchValues('style');
       $this->assertArraySubset(self::TEST_VALUE, $values);
   }


}