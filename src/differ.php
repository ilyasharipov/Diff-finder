<?php

namespace DiffFinder\differ;

use function \Funct\Collection\union;

function genDiff($firstFile, $secondFile)
{
    $jsoncontent1 = file_get_contents($firstFile);
    $jsoncontent2 = file_get_contents($secondFile);
    //print_r($jsoncontent1);
    $jsondecode1 = json_decode($jsoncontent1, true);
    $jsondecode2 = json_decode($jsoncontent2, true);
    //print_r($aa);
    //print_r($bb);
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

//print_r($dataResult);

function getRender($data)
{
    $result = array_reduce($data, function ($acc, $item) {
        if ($item['status'] === 'added') {
            $acc = "{$acc} + {$item['key']}: {$item['value']}\n";
        } elseif ($item['status'] === 'deleted') {
            $acc = "{$acc} - {$item['key']}: {$item['value']}\n";
        } elseif ($item['status'] === 'changed') {
            $acc = "{$acc} + {$item['key']}: {$item['modified']}\n - {$item['key']}: {$item['previous']}\n";
        } elseif ($item['status'] === 'unchanged') {
            $acc = "{$acc}   {$item['key']}: {$item['value']}\n";
        }
    //print_r($acc);
    
        return $acc;
    }, '');
  //print_r($result);
    return "{" . "\n" . $result . "}";
}
