<?php

namespace GenDiff\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function GenDiff\genDiff;
use function GenDiff\Parsers\parseFile;
use function GenDiff\DiffAst\makeDiffAst;
use function GenDiff\ToString\toString;

class GenDiffTest extends TestCase
{
    public function testParsers()
    {
        $pathToFile1 = __DIR__ . '/fixtures/before.json';
        $pathToFile2 = __DIR__ . '/fixtures/after.json';

        $pathToFile3 = __DIR__ . '/fixtures/before.yaml';
        $pathToFile4 = __DIR__ . '/fixtures/after.yaml';

        $this->assertTrue(is_array(parseFile($pathToFile1)));
        $this->assertTrue(is_array(parseFile($pathToFile2)));
        $this->assertTrue(is_array(parseFile($pathToFile3)));
        $this->assertTrue(is_array(parseFile($pathToFile4)));
    }

    public function testGenDiff()
    {
        $pathToFile1 = __DIR__ . '/fixtures/before.json';
        $pathToFile2 = __DIR__ . '/fixtures/after.json';

        $pathToFile3 = __DIR__ . '/fixtures/before.yaml';
        $pathToFile4 = __DIR__ . '/fixtures/after.yaml';

        $pathToFile5 = __DIR__ . '/fixtures/before2.json';
        $pathToFile6 = __DIR__ . '/fixtures/after2.json';

        $pathToFile7 = __DIR__ . '/fixtures/before2.yaml';
        $pathToFile8 = __DIR__ . '/fixtures/after2.yaml';

        $result1 = file_get_contents('tests/fixtures/result-plain');
        $result2 = file_get_contents('tests/fixtures/result-nested');

        $this->assertEquals($result1, genDiff($pathToFile1, $pathToFile2));
        $this->assertEquals($result1, genDiff($pathToFile3, $pathToFile4));
        $this->assertEquals($result2, genDiff($pathToFile5, $pathToFile6));
        $this->assertEquals($result2, genDiff($pathToFile7, $pathToFile8));
    }
}
