<?php

namespace DiffFinder\formatters\plain;

function getPlainData($ast, $nestedPart = "")
{
    $result = array_reduce($ast, function ($acc, $node) use ($nestedPart) {
        if ($node["type"] === "added") {
            $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was added with value: " .
            (is_object($node['afterValue']) ? "'complex value'" : "'" . $node['afterValue'] . "'");
        } elseif ($node["type"] === "deleted") {
            $acc[] = "Property " . "'" . $nestedPart . $node["key"] . "'" . " was removed";
        } elseif ($node["type"] === "changed") {
            $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was changed. " .
            "From " . "'" . $node["beforeValue"] . "'" . " to " . "'" . $node["afterValue"] . "'";
        } elseif ($node["type"] === "nested") {
            $acc[] = getPlainData($node['children'], $node['key'] . ".");
        }

        return $acc;
    }, []);
    
    $strResult = implode("\n", $result);
    return $strResult;
}
