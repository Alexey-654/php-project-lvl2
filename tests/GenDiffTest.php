<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;

use function GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testGenDiff($fileNameExpected, $fileNameBefore, $fileNameAfter, $format)
    {
        $dirWithFiles = __DIR__ . '/fixtures/';
        $getExpectedFile = fn ($fileName) => file_get_contents($dirWithFiles . $fileName);
        $getPathToFile = fn ($fileName) => $dirWithFiles . $fileName;

        $this->assertEquals(
            $getExpectedFile($fileNameExpected),
            genDiff($getPathToFile($fileNameBefore), $getPathToFile($fileNameAfter), $format)
        );
    }

    public function provider()
    {
        return [
            ['pretty', 'before.json', 'after.json', 'pretty'],
            ['pretty', 'before.yaml', 'after.yaml', 'pretty'],
            ['plain', 'before.json', 'after.json', 'plain'],
            ['plain', 'before.yaml', 'after.yaml', 'plain'],
            ['json.json', 'before.json', 'after.json', 'json'],
            ['json.json', 'before.yaml', 'after.yaml', 'json'],
        ];
    }
}
