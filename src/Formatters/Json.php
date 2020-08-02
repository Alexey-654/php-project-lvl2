<?php

namespace GenDiff\Formatters\Json;

use function GenDiff\Formatters\Pretty\renderAst;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;

function toJsonFormat($difTreeAst)
{
    $renderedArray = renderAst($difTreeAst);
    $json = json_encode($renderedArray, JSON_PRETTY_PRINT);

    return $json;
}
