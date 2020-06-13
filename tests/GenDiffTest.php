<?php

namespace GenDiff\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function GenDiff\GenDiff\genDiff;
use function GenDiff\Parsers\parse;

class GenDiffTest extends TestCase
{
    public function testParsers()
    {
        $pathToFile1 = 'tests/fixtures/before.json';
        $pathToFile2 = 'tests/fixtures/after.json';

        $pathToFile3 = 'tests/fixtures/before.yaml';
        $pathToFile4 = 'tests/fixtures/after.yaml';

        $this->assertTrue(is_array(parse($pathToFile1)));
        $this->assertTrue(is_array(parse($pathToFile2)));
        $this->assertTrue(is_array(parse($pathToFile3)));
        $this->assertTrue(is_array(parse($pathToFile4)));
    }

    public function testGenDiff()
    {
        $pathToFile1 = 'tests/fixtures/before.json';
        $pathToFile2 = 'tests/fixtures/after.json';

        $pathToFile3 = 'tests/fixtures/before.yaml';
        $pathToFile4 = 'tests/fixtures/after.yaml';

        $result = file_get_contents('tests/fixtures/result.json');

        $this->assertEquals($result, genDiff($pathToFile1, $pathToFile2));
        $this->assertEquals($result, genDiff($pathToFile3, $pathToFile4));
    }
}
