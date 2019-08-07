<?php

namespace DiffFinder\differ;

use function DiffFinder\parser\getFileFormatData;
use function DiffFinder\AstBuilder\getAst;
use function DiffFinder\Render\getRender;
use function  DiffFinder\format\Plain\getPlainData;

function genDiff($firstFile, $secondFile, $format = 'all')
{
    $firstFileFormat = pathinfo($firstFile, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondFile, PATHINFO_EXTENSION);

    $firstFileData = file_get_contents($firstFile);
    $secondFileData = file_get_contents($secondFile);

    $firstFileDataDecode = getFileFormatData($firstFileData, $firstFileFormat);
    $secondFileDataDecode = getFileFormatData($secondFileData, $secondFileFormat);

    $dataFileResult = getAst($firstFileDataDecode, $secondFileDataDecode);

    if ($format === 'plain') {
        return getPlainData($dataFileResult);
    } elseif ($format === 'all') {
        return getRender($dataFileResult);
    }
}
