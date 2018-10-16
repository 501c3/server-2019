<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 10/13/18
 * Time: 9:03 PM
 */

namespace App\Tests\Common;


use App\Common\Position;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PositionTest extends KernelTestCase
{
    public function testPositionParse()
    {
        $actual = Position::strToPos('R10C25');
        $this->assertEquals(10, $actual['row']);
        $this->assertEquals(25, $actual['col']);
    }
}