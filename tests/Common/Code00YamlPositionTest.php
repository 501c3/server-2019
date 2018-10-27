<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/26/18
 * Time: 7:06 PM
 */

namespace Tests\Utils;

use App\Common\YamlPosition;
use App\Common\AppException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;

class Code00YamlPositionTest extends KernelTestCase
{
    //    BY_TEN      = '0        10        20        30        40        50        60        70'
    //    RULER       = '1234567890123456789012345678901234567890123456789012345678901234567890123456789'
    const TEST_LINE_A = 'test-line-a: {substyle: [[dance], [dance], [dance], [dance], [dance]]}';
    const POSITION_A = 'R1C1: {R1C15: [[R1C27], [R1C36], [R1C45], [R1C54], [R1C63]]}';
    const TEST_LINE_B = '<test-line-b+: {abbr: tb+, order: 3}';
    const POSITION_B  = 'R1C1: {R1C17: R1C23, R1C28: R1C35}';


    /**
     * @expectedException  \Exception
     * @expectedExceptionMessage No yaml string to parse
     * @expectedExceptionCode 255
     */
    public function test0000EmptyString()
    {
        YamlPosition::yamlAddPosition( "" );
    }

    /**
     * @throws \Exception
     */
    public function test0000Parse()
    {
        $actual = YamlPosition::strToPos('R10C25');
        $this->assertEquals(10, $actual['row']);
        $this->assertEquals(25, $actual['col']);
    }

    /**
     *
     */
     public function test0000LineA()
    {
        $result = YamlPosition::position( 1, self::TEST_LINE_A );
        $this->assertSame( self::POSITION_A, $result );
    }


    public function test0000LineB()
    {
        $result = YamlPosition::position( 1, self::TEST_LINE_B );
        $this->assertSame(self::POSITION_B, $result);
    }

    /**
     * @throws \Exception
     */
    public function test0010PositionCorrect()
    {
        $string = file_get_contents(__DIR__ . '/data-00-position-0010-isolate.yml');
        $actual = YamlPosition::yamlAddPosition($string);
        $expected = Yaml::parse(file_get_contents(__DIR__ . '/data-00-position-0010-correct.yml'));
        $this->assertEquals($expected,$actual);
        $expectedIsolatedData = Yaml::parse($string);
        $actualIsolatedData = YamlPosition::isolate($actual);
        $this->assertEquals($expectedIsolatedData, $actualIsolatedData);
    }

    /**
     * @throws AppException
     */
    public function test0020PositionKey()
    {
        $testArray = ['key1', 'key2', 'key3'];
        $actualKey1TrueInCollection = YamlPosition::inCollection('key1', $testArray);
        $actualKey3TrueInCollection = YamlPosition::inCollection('key3', $testArray);
        $actualKey99FalseInCollection = YamlPosition::inCollection('key99', $testArray);
        $this->assertTrue($actualKey1TrueInCollection);
        $this->assertTrue($actualKey3TrueInCollection);
        $this->assertFalse($actualKey99FalseInCollection);
    }

    /**
     * @expectedException \App\Common\AppException
     * @expectedExceptionMessage 'key99' at (row:10,col:10). Expected [key1, key2, key3].
     * @expectedExceptionCode \App\Common\AppExceptionCodes::NOT_IN_COLLECTION
     * @throws AppException
     */
    public function test0030PositionInCollection()
    {
        $testArray = ['key1', 'key2', 'key3'];
        $actualKey1TrueInCollection = YamlPosition::inCollection('key1|R2C1', $testArray);
        $actualKey3TrueInCollection = YamlPosition::inCollection('key3|R4C1', $testArray);
        YamlPosition::inCollection('key99|R10C10', $testArray); #Throws exception
        $this->assertTrue($actualKey1TrueInCollection);
        $this->assertTrue($actualKey3TrueInCollection);
    }

    /**
     * @throws \Exception
     */
    public function test0040PositionIsolate()
    {
        $string = file_get_contents(__DIR__ . '/data-00-position-0010-isolate.yml');
        $expected = Yaml::parse($string);
        $dataPosition = YamlPosition::yamlAddPosition($string);
        $actual = YamlPosition::isolate($dataPosition);
        $this->assertEquals($expected,$actual);
    }







 }