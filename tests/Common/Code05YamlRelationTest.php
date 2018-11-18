<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/31/18
 * Time: 3:05 PM
 */

namespace App\Tests\Common;


use App\Common\YamlRelations;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class Code05YamlRelationTest extends KernelTestCase
{
  private $ageRangeFor=[];

  /** @var YamlRelations */
  private $yamlRelations;

  private function setUpRanges()
  {
      for($i=1;$i<100;$i++){
          $year='Y'.str_pad($i,2,'0');
          if ($i <= 4) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(1,$i),
                   'invalid'=>range($i+1,99)];
          } elseif ($i <= 6) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(4,$i),
                   'invalid'=>array_merge(range(1,3),range($i+1,99))];
          } elseif ($i <= 9) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(5,$i),
                   'invalid'=>array_merge(range(1,4),range($i+1,99))];
          } elseif ($i <= 11) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(7,$i),
                   'invalid'=>array_merge(range(1,6),range($i+1,99))];
          } elseif ($i <= 13) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(10,$i),
                   'invalid'=>array_merge(range(1,9),range($i+1,99))];
          } elseif ($i <= 15) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(12,$i),
                   'invalid'=>array_merge(range(1,11),range($i+1,99))];
          } elseif ($i <= 18) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(14,$i),
                   'invalid'=>array_merge(range(1,13),range($i+1,99))];
          } elseif ($i < 35) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(16,$i),
                   'invalid'=>array_merge(range(1,15),range($i+1,99))];
          } elseif ($i < 45) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(30,$i),
                   'invalid'=>array_merge(range(1,29),range($i+1,99))];
          } elseif ($i < 55) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(40,$i),
                   'invalid'=>array_merge(range(1,39),range($i+1,99))];
          } elseif ($year < 65){
              $this->ageRangeFor[$year]=
                  ['valid'=>range(50,$i),
                   'invalid'=>array_merge(range(1,49),range($i+1,99))];
          } elseif ($year < 75) {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(60,$i),
                   'invalid'=>array_merge(range(1,59),range($i+1,99))];
          } else {
              $this->ageRangeFor[$year]=
                  ['valid'=>range(70,$i),
                   'invalid'=>array_merge(range(1,69),range($i+1,99))];
          }

      }
  }

    /**
     * @throws \App\Common\AppException
     */
  public function setUp()
  {
      $this->yamlRelations = new YamlRelations();
      $this->yamlRelations->declareModels(__DIR__ . '/models.yml');
      $this->yamlRelations->declareDomains(__DIR__ . '/domains.yml');
      $this->yamlRelations->declareValues(__DIR__ . '/values.yml');
      $this->yamlRelations->declarePersons(__DIR__ . '/persons.yml');
      $this->yamlRelations->declareTeams(__DIR__.'/teams.yml');
      $this->yamlRelations->declareEventValues(__DIR__.'/event-values.yml');
      $this->yamlRelations->declareEvents(__DIR__.'/model-events.yml');
      $this->setUpRanges();
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid-key' at (row:2,col:1). Expected [competition, team-person, team-event]
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0510RelationsInvalidKey()
  {
     $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0510-invalid-key.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid-key' at (row:5,col:3). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0520RelationsInvalidKey()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0520-invalid-key.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Team Type' at (row:3,col:9). Expected [Amateur, Professional,
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0530RelationsInvalidTeamType()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0530-invalid-team-type.yml');
  }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Team Status' at (row:4,col:11). Expected [Teacher,
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0540RelationsInvalidTeamStatus()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0540-invalid-team-status.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid-Sex' at (row:6,col:5). Expected [Male, Female, Male-Male,
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0550RelationsTeamSex()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0550-team-sex.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'nan-15' at (row:7,col:8) which is not a valid range.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_RANGE
     */
  public function test0560RelationsTeamAge()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0560-team-age.yml');
  }


    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found '15-14' at (row:7,col:8) which is not a valid range.
     * @expectedExceptionCode \App\Common\AppExceptionCodes::INVALID_RANGE
     */
  public function test0570RelationsTeamAgeRange()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0570-team-age-range.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Found 'Invalid Proficiency' at (row:9,col:16). Expected [Social, Newcomer,
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0580RelationsTeamPartnerProficiency()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0580-team-partner-proficiency.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage  Empty array expected near Newcomer at (row:9,col:5).
     * @expectedExceptionCode \App\Common\AppExceptionCodes::EMPTY_ARRAY_EXPECTED
     *
     */
  public function test0590RelationsTeamEmptyListExpected()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0590-team-empty-list.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Missing partner values near Newcomer (row:9,col:5).
     * @expectedExceptionCode \App\Common\AppExceptionCodes::PARTNER_VALUES
     */
  public function test0610RelationsTeamMissingPartner()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0610-team-missing-partner.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'invalid_key' at (row:16,col:5). Expected [type, status, sex, age, proficiency].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0630RelationsEventInvalidKey()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0630-event-invalid-key.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Value' at (row:15,col:24). Expected [Student-Student].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0640RelationsEventInvalidValue()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0640-event-invalid-value.yml');
  }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage Found 'Invalid Age' at (row:19,col:13).
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     */
  public function test0650RelationsEventInvalidAge()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0650-event-invalid-age.yml');
  }

    /**
     * @throws \App\Common\AppException
     */
  public function test0660RelationsEventToFile()
  {
      $this->yamlRelations->declareRelations(__DIR__.'/data-05-relations-0660-event-to-file.yml');
      $this->assertFileExists('/tmp/gads/team-person/tp0000001.yaml');
      $this->assertFileExists('/tmp/gads/team-event/te0000001.yaml');

  }
}