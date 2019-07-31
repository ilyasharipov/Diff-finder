<?php

namespace DiffFinder\AstBuilder;

use function \Funct\Collection\union;

function buildNode($key, $beforeValue, $afterValue, $status, $children)
{
    return [
        'key' => $key,
        'beforeValue' => $beforeValue,
        'afterValue' => $afterValue,
        'status' => $status,
        'children' => $children
    ];
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }

    return $value;
}

function getAst(object $beforeFile, object $afterFile) : array
{
    $beforeFileData = get_object_vars($beforeFile);
    $afterFileData = get_object_vars($afterFile);

    $keys = union(array_keys($beforeFileData), array_keys($afterFileData));

    $ast = array_reduce($keys, function ($acc, $key) use ($beforeFileData, $afterFileData) {
        $beforeValue = $beforeFileData[$key] ?? null;
        $afterValue = $afterFileData[$key] ?? null;
        $beforeValue = boolToString($beforeValue);
        $afterValue = boolToString($afterValue);

        if (!isset($beforeValue)) {
            $acc[] = buildNode($key, null, $afterValue, 'added', null);
        } elseif (!isset($afterValue)) {
            $acc[] = buildNode($key, $beforeValue, null, 'deleted', null);
        } elseif (isset($beforeValue) && isset($afterValue)) {
            if (is_object($beforeValue) && is_object($afterValue)) {
                $acc[] = buildNode($key, null, null, 'nested', getAst($beforeValue, $afterValue));
            } elseif ($beforeValue !== $afterValue) {
                $acc[] = buildNode($key, $beforeValue, $afterValue, 'changed', null);
            } elseif ($beforeValue === $afterValue) {
                $acc[] = buildNode($key, $afterValue, $afterValue, 'unchanged', null);
            } 
        }
        
        return $acc;
    }, []);
      
    return $ast;
}
