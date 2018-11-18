<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 7:00 PM
 */

namespace App\Tests\Repository;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Code06ModelDomainValueTest extends KernelTestCase
{
   public static function setUpBeforeClass()
   {
       (new Dotenv())->load( __DIR__ . '/../../.env' );
   }

   public function setUp()
   {
       self::$kernel = self::bootKernel();
   }



}