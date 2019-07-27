<?php

namespace DiffFinder\differ;

use function \Funct\Collection\union;
use function DiffFinder\parser\getFileFormatData;

function genDiff($firstFile, $secondFile)
{
    $firstFileFormat = pathinfo($firstFile, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondFile, PATHINFO_EXTENSION);

    $firstFileData = file_get_contents($firstFile);
    $secondFileData = file_get_contents($secondFile);

    $firstFileDataDecode = getFileFormatData($firstFileData, $firstFileFormat);
    $secondFileDataDecode = getFileFormatData($secondFileData, $secondFileFormat);

    $dataFileResult = getDiff($firstFileDataDecode, $secondFileDataDecode);
    return getRender($dataFileResult);
}

function getDiff($f1, $f2)
{
    $data1 = get_object_vars($f1);
    $data2 = get_object_vars($f2);
    print_r($data1);
    $keys = union(array_keys($data1), array_keys($data2));

    $result = array_map(function ($key) use ($data1, $data2) {
        if (!isset($data1[$key])) {
            return [
            'key' => $key,
            'value' => $data2[$key],
            'status' => 'added'
            ];
        } elseif (!isset($data2[$key])) {
            return [
            'key' => $key,
            'value' => $data1[$key],
            'status' => 'deleted'
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            return [
            'key' => $key,
            'modified' => $data2[$key],
            'previous' => $data1[$key],
            'status' => 'changed'
            ];
        } elseif ($data1[$key] === $data2[$key]) {
            return [
            'key' => $key,
            'value' => $data1[$key],
            'status' => 'unchanged'
            ];
        }
    }, $keys);
      
    return $result;
}

function getRender($data) //buildOutput
{
    $result = array_reduce($data, function ($acc, $item) {
        if ($item['status'] === 'added') {
            $acc .= " + " . $item['key'] . ": " . boolToString($item['value']) . "\n";
        } elseif ($item['status'] === 'deleted') {
            $acc .= " - " . $item['key'] . ": " . boolToString($item['value']) . "\n";
        } elseif ($item['status'] === 'changed') {
            $acc .= " + " . $item['key'] . ": " . boolToString($item['modified']) . "\n" .
            " - " . $item['key'] . ": " . boolToString($item['previous']) . "\n";
        } elseif ($item['status'] === 'unchanged') {
            $acc .= "   " . $item['key'] . ": " . boolToString($item['value']) . "\n";
        }
    
        return $acc;
    }, '');

    return "{" . "\n" . $result . "}";
}


function boolToString($value)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }

    return $value;
}
