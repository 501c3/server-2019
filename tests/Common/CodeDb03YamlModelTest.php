<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/27/18
 * Time: 10:47 AM
 */

namespace App\Tests\Common;

use App\Common\YamlDbModel;
use App\Entity\Models\Domain;
use App\Entity\Models\Model;
use App\Entity\Models\Person;
use App\Entity\Models\Team;
use App\Entity\Models\Value;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;


class CodeDb03YamlModelTest extends KernelTestCase
{
    const
        MEDAL_PROFICIENCIES = ['Pre Bronze','Bronze','Silver','Gold','Gold Star 1','Gold Star 2'],
        AMATEUR_PROFICIENCIES = ['Social','Newcomer','Bronze','Silver','Gold','Novice','Pre Championship','Championship'],
        PRO_AM_PROFICIENCIES = ['Pre Bronze','Intermediate Bronze','Full Bronze','Open Bronze',
                                'Pre Silver','Intermediate Silver','Full Silver','Open Silver',
                                'Pre Gold','Intermediate Gold','Full Gold','Open Gold',
                                'Gold Star 1','Gold Star 2','Rising Star','Professional'];




    /** @var \App\Kernel */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        (new Dotenv())->load(__DIR__.'/../../.env');
    }

    /**
     * @var YamlDbModel
     */
    private $yamlDbModel;

    private $repository;

    /**
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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setUp()
    {
        self::$kernel = self::bootKernel();
        $container = self::$kernel->getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.models_entity_manager');
        $this->clearDb($entityManager);
        $connection = $entityManager->getConnection();
        $dumpLoad = file_get_contents(__DIR__.'/data-dump-01-person.sql');
        $connection->query($dumpLoad);
        $this->repository = $entityManager->getRepository(Team::class);
        $action = $this->yamlDbModel;
        /* Pull search arrays from the database.  They were tested in CodeDb{01..02}YamlModelTest.php */
        $action->fetchQuickSearch(Model::class);
        $action->fetchQuickSearch(Domain::class);
        $action->fetchQuickSearch(Value::class);
        $action->fetchQuickSearch(Person::class);
    }

    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid_key' at (row:7,col:3). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws \App\Common\AppException
     */
    public function test0350TeamsInvalidKey()
    {
        $this->yamlDbModel->declareTeams(__DIR__ . '/data-03-teams-0350-invalid-key.yml');
    }


    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Missing sex,age between rows 1 and 3.
     # @expectedExceptionMessage \App\Common\AppExceptionCode::MISSING_KEYS
     * @throws \App\Common\AppException
     */
    public function test0360TeamsMissingKey()
    {
        $this->yamlDbModel->declareTeams(__DIR__ . '/data-03-teams-0360-missing-key.yml');
    }

    /**
     * @expectedException  \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Value' at (row:8,col:5) which is not a recognized value.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::UNRECOGNIZED_VALUE
     */
    public function test0370TeamsInvalidValue()
    {
        $this->yamlDbModel->declareTeams(__DIR__ . '/data-03-teams-0370-invalid-value.yml');
    }

    /**
     * @throws \App\Common\AppException
     */
    public function test0380TeamsQuickSearch()
    {
        $expect = $this->yamlDbModel->declareTeams(__DIR__ . '/teams.yml');
        $actual = $this->yamlDbModel->fetchQuickSearch(Team::class);
        foreach($expect as $type=>$statusList) {
            $this->assertArrayHasKey($type, $actual);
            foreach($statusList as $status=>$sexList) {
                $this->assertArrayHasKey($status, $actual[$type]);
                foreach($sexList as $sex=>$ageList) {
                    $this->assertArrayHasKey($sex,$actual[$type][$status]);
                    foreach($ageList as $age=>$proficiencyList){
                        $this->assertArrayHasKey($age,$actual[$type][$status][$sex]);
                        /**
                         * @var string  $proficiency
                         * @var Team $team
                         */
                        foreach($proficiencyList as $proficiency=>$team){
                            $expected = ['type'=>$type,
                                         'status'=>$status,
                                         'sex'=>$sex,
                                         'age'=>$age,
                                         'proficiency'=>$proficiency];
                            $description = $team->getDescription();
                            $this->assertEquals($expected,$description);
                        }
                    }
                }
            }
        }
    }
}