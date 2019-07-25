<?php

namespace DiffFinder\differ;

use function \Funct\Collection\union;

function genDiff($firstFile, $secondFile)
{
    $jsoncontent1 = file_get_contents($firstFile);
    $jsoncontent2 = file_get_contents($secondFile);

    $jsondecode1 = json_decode($jsoncontent1, true);
    $jsondecode2 = json_decode($jsoncontent2, true);

    $dataResult = getDiff($jsondecode1, $jsondecode2);
    return getRender($dataResult);
}

function getDiff($data1, $data2)
{
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
