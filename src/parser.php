<?php

namespace DiffFinder\parser;

use Symfony\Component\Yaml\Yaml;

function getFileFormatData($data, $fileFormat)
{
    if ($fileFormat === 'json') {
        return json_decode($data);
    } elseif ($fileFormat === 'yml') {
        return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}
