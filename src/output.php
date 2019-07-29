<?php

namespace DiffFinder\Render;

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
