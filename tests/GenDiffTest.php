<?php

namespace GenDiff\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expectedResult1 = file_get_contents('tests/fixtures/result-pretty');
        $expectedResult2 = file_get_contents('tests/fixtures/result-pretty-nested');
        $expectedResult3 = file_get_contents('tests/fixtures/result-plain');
        $expectedResult4 = file_get_contents('tests/fixtures/result-plain-nested');
        $expectedResult5 = file_get_contents('tests/fixtures/result-json.json');
        $expectedResult6 = file_get_contents('tests/fixtures/result-json-nested.json');

        $pathToFile1 = __DIR__ . '/fixtures/before.json';
        $pathToFile2 = __DIR__ . '/fixtures/after.json';
        $pathToFile3 = __DIR__ . '/fixtures/before.yaml';
        $pathToFile4 = __DIR__ . '/fixtures/after.yaml';
        $pathToFile5 = __DIR__ . '/fixtures/before2.json';
        $pathToFile6 = __DIR__ . '/fixtures/after2.json';
        $pathToFile7 = __DIR__ . '/fixtures/before2.yaml';
        $pathToFile8 = __DIR__ . '/fixtures/after2.yaml';

        $formatPretty = 'pretty';
        $formatPlain = 'plain';
        $formatJson = 'json';

        $this->assertEquals($expectedResult1, genDiff($pathToFile1, $pathToFile2, $formatPretty));
        $this->assertEquals($expectedResult1, genDiff($pathToFile3, $pathToFile4, $formatPretty));
        $this->assertEquals($expectedResult2, genDiff($pathToFile5, $pathToFile6, $formatPretty));
        $this->assertEquals($expectedResult2, genDiff($pathToFile7, $pathToFile8, $formatPretty));

        $this->assertEquals($expectedResult3, genDiff($pathToFile1, $pathToFile2, $formatPlain));
        $this->assertEquals($expectedResult3, genDiff($pathToFile3, $pathToFile4, $formatPlain));
        $this->assertEquals($expectedResult4, genDiff($pathToFile5, $pathToFile6, $formatPlain));
        $this->assertEquals($expectedResult4, genDiff($pathToFile7, $pathToFile8, $formatPlain));

        $this->assertEquals($expectedResult5, genDiff($pathToFile1, $pathToFile2, $formatJson));
        $this->assertEquals($expectedResult5, genDiff($pathToFile3, $pathToFile4, $formatJson));
        $this->assertEquals($expectedResult6, genDiff($pathToFile5, $pathToFile6, $formatJson));
        $this->assertEquals($expectedResult6, genDiff($pathToFile7, $pathToFile8, $formatJson));
    }
}
