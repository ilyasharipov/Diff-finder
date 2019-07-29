<?php

namespace DiffFinder\AstBuilder;

use function \Funct\Collection\union;

function getAst($f1, $f2)
{
    $data1 = get_object_vars($f1);
    $data2 = get_object_vars($f2);
    //print_r($data1);
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
