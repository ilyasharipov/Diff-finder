<?php

namespace DiffFinder\Tests;

use function DiffFinder\differ\genDiff;
use PHPUnit\Framework\TestCase;

class differTest extends TestCase
{
    public function testGetDiffJson()
    {
        $pathToFirstFile = 'tests/files/before.json';
        $pathToSecondFile = 'tests/files/after.json';
        $expected = file_get_contents('tests/files/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }

    public function testGetDiffYml()
    {
        $pathToFirstFile = 'tests/files/before.yml';
        $pathToSecondFile = 'tests/files/after.yml';
        $expected = file_get_contents('tests/files/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }
}
