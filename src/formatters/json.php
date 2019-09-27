<?php

namespace DiffFinder\formatters\json;

function getJson($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
