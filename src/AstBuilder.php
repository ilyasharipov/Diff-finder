<?php

namespace DiffFinder\AstBuilder;

use function \Funct\Collection\union;

// function buildNode($node)
// {
//     if (is_array($node) {
//         return getAst();
//     }
// }

function getAst($f1, $f2)
{
    $data1 = get_object_vars($f1);
    $data2 = get_object_vars($f2);

    //print_r($data1);

    $keys = union(array_keys($data1), array_keys($data2));

    $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (!isset($data1[$key])) {
            $acc[] = [
            'key' => $key,
            'value' => is_object($data2[$key]) ? getAst($data2[$key], $data2[$key]) : $data2[$key],
            'status' => 'added'
            ];
        } elseif (!isset($data2[$key])) {
            $acc[] = [
            'key' => $key,
            'value' => is_object($data1[$key]) ? getAst($data1[$key], $data1[$key]) : $data1[$key],
            'status' => 'deleted'
            ];
        } elseif ($data1[$key] === $data2[$key]) {
            $acc[] = [
            'key' => $key,
            'value' => is_object($data1[$key]) ? getAst($data1[$key], $data2[$key]) : $data1[$key],
            'status' => 'unchanged'
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            $acc[] = [
            'key' => $key,
            'modified' => is_object($data2[$key]) ? getAst($data2[$key], $data1[$key]) : $data2[$key],
            'previous' => is_object($data1[$key]) ? getAst($data1[$key], $data2[$key]) : $data1[$key],
            'status' => 'changed'
            ];
        }
        
        return $acc;
    }, []);
      
    return $result;
}
