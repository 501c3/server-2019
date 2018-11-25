<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/20/18
 * Time: 12:37 PM
 */

namespace App\Tests\Common;

use App\Common\YamlDbModel;
use App\Entity\Models\Domain;
use App\Entity\Models\Model;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class CodeDb01YamlModelTest extends KernelTestCase
{
    const TEST_VALUE
        = [
            'International' => ['abbr' => "I"],
            'American' =>['abbr' =>"A"],
            'Country' =>['abbr' =>"C"],
            'Fun Events' =>['abbr' => "F"]
        ];

    /** @var \App\Kernel */
   protected static $kernel;

   /** @var EntityManagerInterface */
   private $entityManagerModels;

   public static function setUpBeforeClass()
   {
       (new Dotenv())->load(__DIR__.'/../../.env');
   }

    /**
     * @var YamlDbModel
     */
   private $yamlDbModel;


   public function setUp()
   {

      self::$kernel = self::bootKernel();
      /** @var EntityManagerInterface $entityManager */
      $entityManager = self::$kernel->getContainer()->get('doctrine.orm.models_entity_manager');
      $this->yamlDbModel = new YamlDbModel($entityManager);
      $purger = new ORMPurger($entityManager);
      $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
      $connection = $purger->getObjectManager()->getConnection();
      $connection->query('SET FOREIGN_KEY_CHECKS=0');
      $purger->purge();
      $connection->query('SET FOREIGN_KEY_CHECKS=1');
   }

   private function getRepository($className)
   {
       $em = self::$kernel->getContainer()->get('doctrine.orm.models_entity_manager');
       return $em->getRepository($className);
   }


   public function test0110Model()
   {
       /** @var EntityManagerInterface $em */
       $repository = $this->getRepository(Model::class);
       $this->yamlDbModel->declareModels(__DIR__ . '/../../tests/Common/models.yml');
       $models=$repository->readMulti();
       $this->assertEquals(3,count($models));
   }


   public function test0120Domain()
   {
       $repository = $this->getRepository(Domain::class);
       $this->yamlDbModel->declareDomains(__DIR__ . '/../../tests/Common/domains.yml');
       $domains = $repository->readMulti();
       $this->assertEquals(11,count($domains));
   }



    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_domain' at (row:6,col:1).
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
   public function test0130ValuesBadDomain()
   {
       $this->yamlDbModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlDbModel->declareValues(__DIR__ . '/data-01-values-0130-bad-domain.yml');

   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'bad_key' at (row:10,col:13). Expected [abbr, note, domain, label].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
   public function test0140ValueBadKey()
   {
       $this->yamlDbModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlDbModel->declareValues(__DIR__ . '/data-01-values-0140-bad-key.yml');
   }


    /**
     * @throws \App\Common\AppException
     */
    public function test0160ValueConfirm()
   {
       $this->yamlDbModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlDbModel->declareValues(__DIR__ . '/values.yml');
       $values=$this->yamlDbModel->fetchValueStrings('style');
       $this->assertArraySubset(array_keys(self::TEST_VALUE), $values);
   }

   public function test0170ModelValue()
   {
       $modelsObjects = $this->yamlDbModel->declareModels(__DIR__ . '/models.yml');
       $modelList = array_keys($modelsObjects);
       $this->yamlDbModel->declareDomains(__DIR__ . '/domains.yml');
       $this->yamlDbModel->declareValues(__DIR__ . '/values.yml');
       $modelValues = $this->yamlDbModel->declareEventValues(__DIR__ . '/event-values.yml');
       foreach ($modelValues as $model=>$domainValues) {
           $this->assertTrue(in_array($model, $modelList));
       }
   }

}