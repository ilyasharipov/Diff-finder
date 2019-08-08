<?php

namespace DiffFinder\format\Json;

function getJson($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
