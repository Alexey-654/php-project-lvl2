<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;

use function GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testGenDiff($expected, $pathToFileBefore, $pathToFileAfter, $format)
    {
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter, $format));
    }

    public function provider()
    {
        $dirWithFiles = __DIR__ . '/fixtures/';
        $getResultFile = fn ($fileName) => file_get_contents($dirWithFiles . $fileName);
        $getPathToFile = fn ($fileName) => $dirWithFiles . $fileName;
        $formatsMap = ['pretty', 'plain', 'json'];

        return [
            [$getResultFile('pretty'), $getPathToFile('before.json'), $getPathToFile('after.json'), $formatsMap[0]],
            [$getResultFile('pretty'), $getPathToFile('before.yaml'), $getPathToFile('after.yaml'), $formatsMap[0]],
            [$getResultFile('plain'), $getPathToFile('before.json'), $getPathToFile('after.json'), $formatsMap[1]],
            [$getResultFile('plain'), $getPathToFile('before.yaml'), $getPathToFile('after.yaml'), $formatsMap[1]],
            [$getResultFile('json.json'), $getPathToFile('before.json'), $getPathToFile('after.json'), $formatsMap[2]],
            [$getResultFile('json.json'), $getPathToFile('before.yaml'), $getPathToFile('after.yaml'), $formatsMap[2]],
        ];
    }
}
