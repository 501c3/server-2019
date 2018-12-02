<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 7:00 PM
 */

namespace App\Tests\Repository\Models;


use App\Entity\Models\Competition;
use App\Entity\Models\Domain;
use App\Entity\Models\Model;
use App\Entity\Models\Value;
use App\Repository\Model\DomainRepository;
use App\Repository\Model\ModelRepository;
use App\Repository\Model\ValueRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class Code06ModelsRepositoriesTest extends KernelTestCase
{
   public static function setUpBeforeClass()
   {
       (new Dotenv())->load(__DIR__ . '/../../../.env');
   }

   private function fetchEntityManager()
   {
       return self::$kernel->getContainer()->get('doctrine.orm.models_entity_manager');
   }

   public function setUp()
   {
       self::$kernel = self::bootKernel();
       $entityManager = $this->fetchEntityManager();
       $purger = new ORMPurger($entityManager);
       $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
       /** @var \Doctrine\DBAL\Driver\PDOSqlsrv\Connection $connection */
       $connection = $purger->getObjectManager()->getConnection();
       $connection->query('SET FOREIGN_KEY_CHECKS=0');
       $purger->purge();
       $connection->query('SET FOREIGN_KEY_CHECKS=1');
   }



    public function test0010ModelCrud()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->fetchEntityManager();
        /** @var ModelRepository $repository */
        $repository=$em->getRepository(Model::class);
        /** @var Model $model */
        $model1=$repository->create('Test Model 1');
        $this->assertInstanceOf(Model::class,$model1);
        $model2=$repository->create('Test Model 2');
        $model3=$repository->create('Test Model 3');
        $model3->setName('Test Model 3 Updated');
        $repository->update($model3);
        $singleModel=$repository->read($model1->getId());
        $this->assertInstanceOf(Model::class,$singleModel);
        $allModels=$repository->readMulti();
        $this->assertEquals(count($allModels),3);
        $repository->delete($model1->getId());
        $repository->remove($model2);
        $updatedModels=$repository->readMulti();
        $this->assertEquals(count($updatedModels),1);
        $this->assertEquals('Test Model 3 Updated',$updatedModels[0]->getName());
    }


    public function test0020DomainCrud()
    {
        $em = $this->fetchEntityManager();
        /** @var DomainRepository $repository */
        $repository=$em->getRepository(Domain::class);
        /** @var Domain $domain1 */
        $domain1=$repository->create('Test Domain 1',1);
        $domain2=$repository->create('Test Domain 2',2);
        $domain3=$repository->create('Test Domain 3',3);
        $this->assertInstanceOf(Domain::class,$domain1);
        $domain3->setName('Test Domain 3 Updated');
        $repository->update($domain3);
        $singleDomain=$repository->read($domain1->getId());
        $this->assertInstanceOf(Domain::class,$singleDomain);
        $allDomains=$repository->readMulti();
        $this->assertEquals(count($allDomains),3);
        $repository->delete($domain1->getId());
        $repository->remove($domain2);
        $updatedDomains=$repository->readMulti();
        $this->assertEquals(count($updatedDomains),1);
        /** @var Domain $domain0 */
        $domain0 = $updatedDomains[0];
        $this->assertEquals('Test Domain 3 Updated',$domain0->getName());
    }


    public function test0030ValueCrud()
    {
        $em = $this->fetchEntityManager();
        $domain=$em->getRepository(Domain::class)->create('Test Domain',1);
        /** @var ValueRepository $repository */
        $repository=$em->getRepository(Value::class);
        /** @var Value $value1 */
        $value1=$repository->create('Test Value 1','tv1',$domain);
        $value2=$repository->create('Test Value 2','tv2',$domain);
        $value3=$repository->create('Test Value 3','tv3',$domain);
        $this->assertInstanceOf(Value::class,$value1);
        $value3->setName('Test Value 3 updated');
        $allValues=$repository->readMulti();
        $this->assertEquals(count($allValues),3);
        $repository->delete($value1->getId());
        $repository->remove($value2);
        $updatedValues = $repository->readMulti();
        $this->assertEquals(count($updatedValues),1);
        /** @var Value $value0 */
        $value0=$updatedValues[0];
        $this->assertEquals('Test Value 3 updated',$value0->getName());
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function test0040CompetitionCrud()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->fetchEntityManager();
        /** @var ModelRepository $repository */
        $repository=$em->getRepository(Competition::class);
        /** @var Competition $competition1 */
        $competition1=$repository->create('Test Competition 1');
        $competition2=$repository->create('Test Competition 2');
        $competition3=$repository->create('Test Competition 3');
        $this->assertInstanceOf(Competition::class,$competition1);
        $competition3->setName('Test Competition 3 Updated');
        $repository->update($competition3);
        $singleCompetition=$repository->read($competition1->getId());
        $this->assertInstanceOf(Competition::class,$singleCompetition);
        $allCompetitions=$repository->readMulti();
        $this->assertEquals(count($allCompetitions),3);
        $repository->delete($competition1->getId());
        $repository->remove($competition2);
        $updatedCompetitions=$repository->readMulti();
        $this->assertEquals(count($updatedCompetitions),1);
        /** @var Competition $competition0 */
        $competition0=$updatedCompetitions[0];
        $this->assertEquals('Test Competition 3 Updated',$competition0->getName());
    }

}