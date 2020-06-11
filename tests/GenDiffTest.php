<?php 

namespace GenDiff\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function GenDiff\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $pathToFile1 = '/home/alexey/php-project-lvl2/tests/fixtures/before.json';
        $pathToFile2 = '/home/alexey/php-project-lvl2/tests/fixtures/after.json';
        $result1 = file_get_contents('/home/alexey/php-project-lvl2/tests/fixtures/result.json');
        $this->assertEquals($result1, genDiff($pathToFile1, $pathToFile2));

        $pathToFile2 = 'tests/fixtures/before.json';
        $pathToFile3 = 'tests/fixtures/after.json';
        $result2 = file_get_contents('tests/fixtures/result.json');
        $this->assertEquals($result2, genDiff($pathToFile2, $pathToFile3));
    }
}
