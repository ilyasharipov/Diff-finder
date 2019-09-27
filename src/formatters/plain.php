<?php

namespace DiffFinder\formatters\plain;

function getPlainData($ast, $nestedPart = "")
{
    $result = array_reduce($ast, function ($acc, $node) use ($nestedPart) {
        if ($node["status"] === "added") {
            $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was added with value: " .
            (is_object($node['afterValue']) ? "'complex value'" : "'" . $node['afterValue'] . "'");
        } elseif ($node["status"] === "deleted") {
            $acc[] = "Property " . "'" . $nestedPart . $node["key"] . "'" . " was removed";
        } elseif ($node["status"] === "changed") {
            $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was changed. " .
            "From " . "'" . $node["beforeValue"] . "'" . " to " . "'" . $node["afterValue"] . "'";
        } elseif ($node["status"] === "nested") {
            $acc[] = getPlainData($node['children'], $node['key'] . ".");
        }

        return $acc;
    }, []);
    
    $strResult = implode("\n", $result);
    return $strResult;
}
