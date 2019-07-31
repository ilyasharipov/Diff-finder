<?php

namespace DiffFinder\Render;

function getRender($data) //buildOutput
{
    $result = array_reduce($data, function ($acc, $item) {
        if ($item['status'] === 'added') {
            $acc .= "  + " . $item['key'] . ": " . $item['value'] . "\n";
        } elseif ($item['status'] === 'deleted') {
            $acc .= "  - " . $item['key'] . ": " . $item['value'] . "\n";
        } elseif ($item['status'] === 'changed') {
            $acc .= "  + " . $item['key'] . ": " . $item['modified'] . "\n" .
            "  - " . $item['key'] . ": " . $item['previous'] . "\n";
        } elseif ($item['status'] === 'unchanged') {
            $acc .= "    " . $item['key'] . ": " . $item['value'] . "\n";
        }
    
        return $acc;
    }, '');

    return "{" . "\n" . $result . "}";
}
