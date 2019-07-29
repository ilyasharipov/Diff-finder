<?php

namespace DiffFinder\Tests;

use function DiffFinder\differ\genDiff;
use PHPUnit\Framework\TestCase;

class differTest extends TestCase
{
    public function testGetDiffJson()
    {
        $pathToFirstFile = 'tests/files/flat/before.json';
        $pathToSecondFile = 'tests/files/flat/after.json';
        $expected = file_get_contents('tests/files/flat/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }

    public function testGetDiffYml()
    {
        $pathToFirstFile = 'tests/files/flat/before.yml';
        $pathToSecondFile = 'tests/files/flat/after.yml';
        $expected = file_get_contents('tests/files/flat/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }

    public function testGetDiffRecursJson()
    {
        $pathToFirstFile = 'tests/files/recursive/before.json';
        $pathToSecondFile = 'tests/files/recursive/after.json';
        $expected = file_get_contents('tests/files/recursive/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }

    public function testGetDiffRecursYml()
    {
        $pathToFirstFile = 'tests/files/recursive/before.yml';
        $pathToSecondFile = 'tests/files/recursive/after.yml';
        $expected = file_get_contents('tests/files/recursive/expected');
        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile));
    }
}
