<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/27/18
 * Time: 7:50 PM
 */

namespace App\Tests\Common;


use App\Common\YamlDbModel;
use App\Entity\Models\Domain;
use App\Entity\Models\Event;
use App\Entity\Models\Model;
use App\Entity\Models\Person;
use App\Entity\Models\Value;
use App\Entity\Models\Team;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

class CodeDb04YamlModelTest extends KernelTestCase
{
    /** @var YamlDbModel $yamlDbModel */
    private $yamlDbModel;

    /** @var  \App\Kernel $kernel*/
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @throws \Doctrine\DBAL\DBALException
     */
    private function clearDb(EntityManagerInterface $entityManager)
    {

        $this->yamlDbModel = new YamlDbModel($entityManager);
        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $connection = $purger->getObjectManager()->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $purger->purge();
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }


    /**
     * @throws \App\Common\AppException
     * @throws \Doctrine\DBAL\DBALException
     */
   public function setUp()
   {
       self::$kernel = self::bootKernel();
       $container = self::$kernel->getContainer();
       /** @var EntityManagerInterface $entityManager */
       $entityManager = $container->get('doctrine.orm.models_entity_manager');
       $this->clearDb($entityManager);
       /** @var EntityManagerInterface $entityManager */
       $connection = $entityManager->getConnection();
       $dumpLoad = file_get_contents(__DIR__.'/data-dump-02-team.sql');
       $connection->query($dumpLoad);
       $action = $this->yamlDbModel;
       $action->fetchQuickSearch(Model::class);
       $action->fetchQuickSearch(Domain::class);
       $action->fetchQuickSearch(Value::class);
       $action->fetchQuickSearch(Person::class);
       $action->fetchQuickSearch(Team::class);
       $this->yamlDbModel->declareEventValues(__DIR__.'/event-values.yml');
   }
   /**
    * @expectedException  \App\Common\AppException
    * @expectedExceptionMessage 'Invalid Model' at (row:1,col:1) which is not a recognized value.
    * @empectedMessageCode \App\Common\AppExceptionCode::UNRECOGNIZED_VALUE
    */

   public function test0380EventsInvalidModel()
   {
       $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0380-invalid-model.yml');
   }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Key' at (row:2,col:3).
     * @expectedExceptionCode  \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0390EventsInvalidKey()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0390-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Missing sex between rows 2 and 30.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::MISSING_KEYS
     */
    public function test0400EventsMissingKey()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0400-missing-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Proficiency' at (row:3,col:5) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0410EventsInvalidProficiency()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0410-invalid-proficiency.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid key' at (row:5,col:7). Expected [tag, style].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */

    public function test0420EventsInvalidKey()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0420-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Tag' at (row:4,col:12) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     *
     */
    public function test0430EventsInvalidTag()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0430-invalid-tag.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Style' at (row:11,col:9) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0440EventsInvalidStyle()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0440-invalid-style.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Key' at (row:8,col:11). Expected [disposition, substyle].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0450EventsInvalidKey()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0450-invalid-key.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid-disposition' at (row:7,col:24). Expected [multiple-events, single-event].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
    public function test0460EventsInvalidDisposition()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0460-invalid-disposition.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Substyle' at (row:9,col:13) which is not a recognized value
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0470EventsInvalidSubstyle()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0470-invalid-substyle.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Cha Cha' at (row:9,col:22) but expected an array ie dash '-'.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::ARRAY_EXPECTED
     */
    public function test0480EventsDoubleBrackets()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0480-double-brackets.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found array near Cha Cha (row:9,col:23) but expected scaler.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::SCALER_EXPECTED
     */

    public function test0490EventsSingleBracket()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0490-single-brackets.yml');
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Invalid Dance' at (row:10,col:60) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0500EventsInvalidDance()
    {
        $this->yamlDbModel->declareEvents(__DIR__ . '/data-04-events-0500-invalid-dance.yml');
    }

    public function test0510EventsQuickSearch()
    {
        $expect = $this->yamlDbModel->declareEvents(__DIR__.'/model-events.yml');
        $actual = $this->yamlDbModel->fetchQuickSearch(Event::class);
        foreach($expect as $type=>$statusList) {
            $this->assertArrayHasKey($type,$actual);
            foreach($statusList as $status=>$sexList) {
                $this->assertArrayHasKey($status,$actual[$type]);
                foreach($sexList as $sex=>$modelList) {
                    $this->assertArrayHasKey($sex, $actual[$type][$status]);
                    foreach($modelList as $model=>$ageList) {
                        $this->assertArrayHasKey($model,$actual[$type][$status][$sex]);
                        foreach($ageList as $age=>$proficiencyList) {
                            $this->assertArrayHasKey($age,$actual[$type][$status][$sex][$model]);
                            foreach($proficiencyList as $proficiency=>$styleList) {
                                $this->assertArrayHasKey($proficiency,$actual[$type][$status][$sex][$model][$age]);
                                foreach($styleList as $style=>$eventList) {
                                    $this->assertArrayHasKey($style,$actual[$type][$status][$sex][$model][$age][$proficiency]);
                                    $expected = ['type'=>$type,
                                                 'status'=>$status,
                                                 'sex'=>$sex,
                                                 'model'=>$model,
                                                 'age'=>$age,
                                                 'proficiency'=>$proficiency,
                                                 'style'=>$style];
                                    $events = $actual[$type][$status][$sex][$model][$age][$proficiency][$style];
                                    /** @var Event $event */
                                    foreach($events as $event) {
                                        $actualDescription = $event->getDescription();
                                        $this->assertArraySubset($expected,$actualDescription);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}