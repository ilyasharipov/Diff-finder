<?php

namespace DiffFinder\formatters\json;

function getJsonData($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
