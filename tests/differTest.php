<?php

namespace DiffFinder\Tests;

use function DiffFinder\differ\genDiff;
use PHPUnit\Framework\TestCase;

class DifferTest extends TestCase
{
    public function testGetDiffJson()
    {
        $pathToFirstFile = 'tests/files/before.json';
        $pathToSecondFile = 'tests/files/after.json';
        $expected1 = file_get_contents('tests/files/expected');
        $this->assertEquals($expected1, genDiff($pathToFirstFile, $pathToSecondFile));

        $expected2 = file_get_contents('tests/files/expectedPlain');
        $this->assertEquals($expected2, genDiff($pathToFirstFile, $pathToSecondFile, 'plain'));
    }

    public function testGetDiffYml()
    {
        $pathToFirstFile = 'tests/files/before.yml';
        $pathToSecondFile = 'tests/files/after.yml';
        $expected1 = file_get_contents('tests/files/expected');
        $this->assertEquals($expected1, genDiff($pathToFirstFile, $pathToSecondFile));

        $expected2 = file_get_contents('tests/files/expectedPlain');
        $this->assertEquals($expected2, genDiff($pathToFirstFile, $pathToSecondFile, 'plain'));
    }
}
