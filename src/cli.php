<?php

namespace DiffFinder\cli;

use function DiffFinder\differ\genDiff;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;

function run()
{
    $args = \Docopt::handle(DOC);
    $diffData = genDiff($args['<firstFile>'], $args['<secondFile>']);
    print_r($diffData . PHP_EOL);
}
