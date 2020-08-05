<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;

use function GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testGenDiff($expected, $file1, $file2, $format)
    {
        $this->assertEquals($expected, genDiff($file1, $file2, $format));
    }

    public function provider()
    {
        $expectedResult1 = file_get_contents('tests/fixtures/results-files/pretty-nested');
        $expectedResult2 = file_get_contents('tests/fixtures/results-files/plain-nested');
        $expectedResult3 = file_get_contents('tests/fixtures/results-files/json-nested.json');

        $pathToFile1 = __DIR__ . '/fixtures/input-files/1-before.json';
        $pathToFile2 = __DIR__ . '/fixtures/input-files/1-after.json';
        $pathToFile3 = __DIR__ . '/fixtures/input-files/2-before.yaml';
        $pathToFile4 = __DIR__ . '/fixtures/input-files/2-after.yaml';

        // $filesMap = scandir(__DIR__ . '/fixtures/input-files');

        $formatsMap = ['pretty', 'plain', 'json'];

        return [
            [$expectedResult1, $pathToFile1, $pathToFile2, $formatsMap[0]],
            [$expectedResult1, $pathToFile3, $pathToFile4, $formatsMap[0]],
            [$expectedResult2, $pathToFile1, $pathToFile2, $formatsMap[1]],
            [$expectedResult2, $pathToFile3, $pathToFile4, $formatsMap[1]],
            [$expectedResult3, $pathToFile1, $pathToFile2, $formatsMap[2]],
            [$expectedResult3, $pathToFile3, $pathToFile4, $formatsMap[2]],
        ];
    }
}
