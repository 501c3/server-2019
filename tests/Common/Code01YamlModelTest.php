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

class Code01YamlModelTest extends KernelTestCase
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
       $this->yamlModel->declareModels(__DIR__ . '/data-01-models.yml');
       $structure = $this->yamlModel->getStructure();
       $count=count($structure);
       $this->assertEquals(3,$count);
   }

   public function test0120Domain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
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
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values-0130-bad-domain.yml');

   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_key' at (row:10,col:13). Expected [abbr, note, domain].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
   public function test0140ValueBadKey()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values-0140-bad-key.yml');
   }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_style'. Expected [style, substyle, status, type, grouping, sex, proficiency, age, tag, dance, genre].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_PARAMETER
     */
   public function test0150ValueBadDomain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values.yml');
       $this->yamlModel->fetchValues('bad_style');
   }


    /**
     * @throws \App\Common\AppException
     */
    public function test0160ValueConfirm()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values.yml');
       $values=$this->yamlModel->fetchValues('style');
       $this->assertArraySubset(self::TEST_VALUE, $values);
   }

   public function test0170ModelValue()
   {
       $this->yamlModel->declareModels(__DIR__ . '/data-01-models.yml');
       $this->yamlModel->declareDomains(__DIR__ . '/data-01-domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values.yml');
       $modelValues = $this->yamlModel->declareModelValues(__DIR__ . '/data-01-values-0000-model.yml');
       var_dump($modelValues);die;
   }


}