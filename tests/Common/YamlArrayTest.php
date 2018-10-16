<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/26/18
 * Time: 7:06 PM
 */

namespace Tests\Utils;

use App\Common\YamlArray;
use App\Common\AppException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;

class YamlArrayTest extends KernelTestCase
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
    public function testEmptyString()
    {
        YamlArray::yamlToStringPosition( "" );
    }

     public function testParseLineA()
    {
        $result = YamlArray::position( 1, self::TEST_LINE_A );
        $this->assertSame( self::POSITION_A, $result );
    }


    public function testParseLineB()
    {
        $result = YamlArray::position( 1, self::TEST_LINE_B );
        $this->assertSame(self::POSITION_B, $result);
    }

    /**
     * @throws \Exception
     */
    public function test10Correct()
    {
        $string = file_get_contents(__DIR__ . '/10-test-input.yml');
        $actual = YamlArray::yamlToStringPosition($string);
        $expected = Yaml::parse(file_get_contents(__DIR__ . '/10-test-position.yml'));
        $this->assertEquals($expected,$actual);
        $expectedIsolatedData = Yaml::parse($string);
        $actualIsolatedData = YamlArray::isolateStringOrPosition($actual);
        $this->assertEquals($expectedIsolatedData, $actualIsolatedData);
    }

    /**
     * @throws AppException
     */
    public function test20ForKey()
    {
        $testArray = ['key1', 'key2', 'key3'];
        $actualKey1TrueInCollection = YamlArray::isInCollection('key1', $testArray);
        $actualKey3TrueInCollection = YamlArray::isInCollection('key3', $testArray);
        $actualKey99FalseInCollection = YamlArray::isInCollection('key99', $testArray);
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
    public function test30KeyValuePairs()
    {
        $testArray = ['key1', 'key2', 'key3'];
        $actualKey1TrueInCollection = YamlArray::isInCollection('key1|R2C1', $testArray);
        $actualKey3TrueInCollection = YamlArray::isInCollection('key3|R4C1', $testArray);
        YamlArray::isInCollection('key99|R10C10', $testArray); #Throws exception
        $this->assertTrue($actualKey1TrueInCollection);
        $this->assertTrue($actualKey3TrueInCollection);
    }

    /**
     * @throws \Exception
     */
    public function test40IsolateStringOrPosition()
    {
        $string = file_get_contents(__DIR__ . '/10-test-input.yml');
        $expected = Yaml::parse($string);
        $dataPosition = YamlArray::yamlToStringPosition($string);
        $actual = YamlArray::isolateStringOrPosition($dataPosition);
        $this->assertEquals($expected,$actual);
    }





 }