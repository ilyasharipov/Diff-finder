<?php

namespace DiffFinder\AstBuilder;

use function \Funct\Collection\union;

function buildNode($key, $beforeValue, $afterValue, $type, $children)
{
    return [
        'key' => $key,
        'beforeValue' => $beforeValue,
        'afterValue' => $afterValue,
        'type' => $type,
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

function getAst($beforeFile, $afterFile)
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
            } else {
                if ($beforeValue === $afterValue) {
                    $acc[] = buildNode($key, $beforeValue, null, 'unchanged', null);
                } else {
                    $acc[] = buildNode($key, $beforeValue, $afterValue, 'changed', null);
                }
            }
        }
        
        return $acc;
    }, []);
    
    return $ast;
}
