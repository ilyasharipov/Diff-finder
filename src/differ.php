<?php

namespace DiffFinder\differ;

use function DiffFinder\parser\getFormatData;
use function DiffFinder\AstBuilder\getAst;
use function DiffFinder\formatters\plain\getPlainData;
use function DiffFinder\formatters\json\getJsonData;
use function DiffFinder\formatters\pretty\getPrettyData;

function genDiff($firstData, $secondData, $format = 'all')
{
    $firstDataFormat = pathinfo($firstData, PATHINFO_EXTENSION);
    $secondDataFormat = pathinfo($secondData, PATHINFO_EXTENSION);

    $firstDataContent = file_get_contents($firstData);
    $secondDataContent = file_get_contents($secondData);

    $firstDataDecode = getFormatData($firstDataContent, $firstDataFormat);
    $secondDataDecode = getFormatData($secondDataContent, $secondDataFormat);

    $dataFileResult = getAst($firstDataDecode, $secondDataDecode);

    if ($format === 'plain') {
        return getPlainData($dataFileResult);
    } elseif ($format === 'json') {
        return getJsonData($dataFileResult);
    }
    
    return getPrettyData($dataFileResult);
}
