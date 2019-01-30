<?php
/**
 * Created by IntelliJ IDEA.
 * User: jakub.polomsky
 * Date: 30.01.2019
 * Time: 07:58
 */

require_once __DIR__ . "/../vendor/autoload.php";

use jakubpolomsky\phpRequirementsChecker\StringFunctions;

class StringFunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testIsValidJson()
    {
        $json1 = "{a:b}";
        $json2 = '{"a":"b"}';

        $this->assertFalse(StringFunctions::isValidJson($json1));
        $this->assertTrue(StringFunctions::isValidJson($json2));
        $this->assertFalse(StringFunctions::isValidJson(""));
    }

    public function testCompareVersions()
    {
        $this->assertTrue(StringFunctions::compareVersions("1.0.0", false));
        $this->assertTrue(StringFunctions::compareVersions("*", ""));
        $this->assertTrue(StringFunctions::compareVersions(">1.0.0", "2.0.0"));
        $this->assertFalse(StringFunctions::compareVersions("<1.0.0", "1.0.0"));
        $this->assertTrue(StringFunctions::compareVersions("1.0.0", "1.0.0"));
    }

    public function testStartsWith()
    {
        $this->assertTrue(StringFunctions::startsWith('ext-intl', 'ext'));
    }

    public function testReplaceFistOccurence()
    {
        $this->assertEquals(StringFunctions::replaceFirstOccurence('ext-', '', 'ext-intl'), 'intl');
        $this->assertEquals(StringFunctions::replaceFirstOccurence('foo-', '', 'ext-intl'), 'ext-intl');
    }
}
