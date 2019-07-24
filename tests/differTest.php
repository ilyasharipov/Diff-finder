<?php

namespace DiffFinder\Tests;

use function DiffFinder\differ\getDiff;
use PHPUnit\Framework\TestCase;

class differTest extends TestCase
{
    public function testGetDiff()
    {
        $result1 = getDiff([], []);
        $this->assertEquals([], $result1);
    }
}