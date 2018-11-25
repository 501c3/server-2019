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
       $this->yamlModel->declareModels(__DIR__ . '/models.yml');
       $structure = $this->yamlModel->fetchModels();
       $count=count($structure);
       $this->assertEquals(3,$count);
   }

   public function test0120Domain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
       $structure = $this->yamlModel->fetchDomains();
       $count=count($structure);
       $this->assertEquals(11,$count);
   }

   /**
    * @expectedException \App\Common\AppException
    * @expectedExceptionMessage Found 'bad_domain' at (row:6,col:1).
    * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
    */
   public function test0130ValuesBadDomain()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values-0130-bad-domain.yml');

   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_key' at (row:10,col:13). Expected [abbr, note, domain, label].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
   public function test0140ValueBadKey()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/data-01-values-0140-bad-key.yml');
   }


    /**
     * @throws \App\Common\AppException
     */
    public function test0160ValueConfirm()
   {
       $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/values.yml');
       $values=$this->yamlModel->fetchValues('style');
       $this->assertArraySubset(self::TEST_VALUE, $values);
   }

    /**
     * @throws \App\Common\AppException
     */
   public function test0170ModelValue()
   {
       $models= $this->yamlModel->declareModels(__DIR__ . '/models.yml');
       $this->yamlModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlModel->declareValues(__DIR__ . '/values.yml');
       $modelValues = $this->yamlModel->declareEventValues(__DIR__ . '/event-values.yml');
       foreach($models as $model) {
           $this->assertArrayHasKey($model,$modelValues);
       }

   }


}