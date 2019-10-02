<?php

namespace DiffFinder\formatters\plain;

function getPlainData($ast, $nestedPart = "")
{
    $result = array_reduce($ast, function ($acc, $node) use ($nestedPart) {
        switch ($node["type"]) {
            case "added":
                $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was added with value: " .
                (is_object($node['afterValue']) ? "'complex value'" : "'" . $node['afterValue'] . "'");
                break;
            case "deleted":
                $acc[] = "Property " . "'" . $nestedPart . $node["key"] . "'" . " was removed";
                break;
            case "changed":
                $acc[] = "Property " . "'" . $nestedPart . $node['key'] . "'" . " was changed. " .
                "From " . "'" . $node["beforeValue"] . "'" . " to " . "'" . $node["afterValue"] . "'";
                break;
            case "nested":
                $acc[] = getPlainData($node['children'], $node['key'] . ".");
                break;
        }

        return $acc;
    }, []);
    
    $strResult = implode("\n", $result);
    return $strResult;
}
