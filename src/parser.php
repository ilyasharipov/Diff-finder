<?php

namespace DiffFinder\parser;

use Symfony\Component\Yaml\Yaml;

function getFormatData($data, $dataFormat)
{
    if ($dataFormat === 'json') {
        return json_decode($data);
    } elseif ($dataFormat === 'yml') {
        return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}
