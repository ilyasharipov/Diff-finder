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

    $dataResult = getAst($firstDataDecode, $secondDataDecode);

    return choseFormatData($dataResult, $format);
}

function choseFormatData($dataResult, $format)
{
    if ($format === 'plain') {
        return getPlainData($dataResult);
    } elseif ($format === 'json') {
        return getJsonData($dataResult);
    }

    return getPrettyData($dataResult);
}
