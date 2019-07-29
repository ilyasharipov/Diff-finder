<?php

namespace DiffFinder\differ;

use function DiffFinder\parser\getFileFormatData;
use function DiffFinder\AstBuilder\getAst;
use function DiffFinder\Render\getRender;

function genDiff($firstFile, $secondFile)
{
    $firstFileFormat = pathinfo($firstFile, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondFile, PATHINFO_EXTENSION);

    $firstFileData = file_get_contents($firstFile);
    $secondFileData = file_get_contents($secondFile);

    $firstFileDataDecode = getFileFormatData($firstFileData, $firstFileFormat);
    $secondFileDataDecode = getFileFormatData($secondFileData, $secondFileFormat);

    $dataFileResult = getAst($firstFileDataDecode, $secondFileDataDecode);
    print_r($dataFileResult);
    return getRender($dataFileResult);
}
