<?php

namespace DiffFinder\Render;

function getRender($ast, $depth = 0)
{
    $indent = str_repeat("    ", $depth);
    $result = array_reduce($ast, function ($acc, $node) use ($indent, $depth) {
        if ($node["status"] === "added") {
            $acc[] = $indent . "  + " . $node["key"] . ": " . objectToStr($node["afterValue"], $depth);
        } elseif ($node["status"] === "deleted") {
            $acc[] = $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["status"] === "changed") {
            $acc[] =  $indent . "  + " . $node['key'] . ": " . objectToStr($node["afterValue"], $depth) . "\n" .
            $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["status"] === 'unchanged') {
            $acc[] = $indent . "    " . $node['key'] . ": " . objectToStr($node["beforeValue"], $depth);
        } elseif ($node["status"] === "nested") {
            $acc[] = $indent . "    " . $node['key'] . ": " . getRender($node['children'], $depth + 1);
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
