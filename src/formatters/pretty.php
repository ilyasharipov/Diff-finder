<?php

namespace DiffFinder\formatters\pretty;

function getPrettyData($ast, $depth = 0)
{
    $indent = str_repeat("    ", $depth);
    $result = array_reduce($ast, function ($acc, $node) use ($indent, $depth) {
        if ($node["type"] === "added") {
            $acc[] = $indent . "  + " . $node["key"] . ": " . objectToStr($node["afterValue"], $depth);
        } elseif ($node["type"] === "deleted") {
            $acc[] = $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["type"] === "changed") {
            $acc[] =  $indent . "  + " . $node['key'] . ": " . objectToStr($node["afterValue"], $depth) . "\n" .
            $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["type"] === 'unchanged') {
            $acc[] = $indent . "    " . $node['key'] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["type"] === "nested") {
            $acc[] = $indent . "    " . $node['key'] . ": " . getPrettyData($node['children'], $depth + 1);
        }

        return $acc;
    }, []);

    $strResult = implode("\n", $result);
    return "{" . "\n" . $strResult . "\n" . $indent . "}";
}

function objectToStr($objData, $depth)
{
    return is_object($objData) ? "{\n    " . arrToString(get_object_vars($objData), $depth) : $objData;
}

function arrToString($data, $depth)
{
    $indent = str_repeat('    ', $depth);
    $keys = array_keys($data);
    $result = array_reduce($keys, function ($acc, $key) use ($data) {
        $acc[] = $key . ": " . $data[$key];

        return $acc;
    }, []);

    $strResult = implode("\n", $result);
    return $indent . "    " . $strResult . "\n" . "    " . $indent . "}";
}
