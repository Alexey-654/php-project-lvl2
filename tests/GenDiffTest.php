<?php 

namespace GenDiff\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function GenDiff\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $pathToFile2 = 'tests/fixtures/before.json';
        $pathToFile3 = 'tests/fixtures/after.json';
        $result2 = file_get_contents('tests/fixtures/result.json');
        $this->assertEquals($result2, genDiff($pathToFile2, $pathToFile3));
    }
}
