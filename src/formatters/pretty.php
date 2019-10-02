<?php

namespace DiffFinder\formatters\pretty;

function getPrettyData($ast, $depth = 0)
{
    $indent = str_repeat("    ", $depth);
    $result = array_reduce($ast, function ($acc, $node) use ($indent, $depth) {
        switch ($node["type"]) {
            case "added":
                $acc[] = $indent . "  + " . $node["key"] . ": " . objectToStr($node["afterValue"], $depth);
                break;
            case "deleted":
                $acc[] = $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
                break;
            case "changed":
                $acc[] =  $indent . "  + " . $node['key'] . ": " . objectToStr($node["afterValue"], $depth) . "\n" .
                $indent . "  - " . $node["key"] . ": " . objectToStr($node["beforeValue"], $depth);
                break;
            case "unchanged":
                $acc[] = $indent . "    " . $node['key'] . ": " . objectToStr($node["beforeValue"], $depth);
                break;
            case "nested":
                $acc[] = $indent . "    " . $node['key'] . ": " . getPrettyData($node['children'], $depth + 1);
                break;
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
