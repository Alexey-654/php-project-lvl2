<?php

namespace GenDiff\Formatters\Json;

use const GenDiff\Diff\NEW_NODE;
use const GenDiff\Diff\DELETED_NODE;
use const GenDiff\Diff\UNCHANGED_NODE;
use const GenDiff\Diff\CHANGED_NODE;
use const GenDiff\Diff\NESTED_NODE;

const NEW_PREFIX = '+ ';
const DEL_PREFIX = '- ';
const BLANK_PREFIX = '  ';



function toJsonFormat($diff)
{
    $renderedNodes = renderDiff($diff);
    $json = json_encode($renderedNodes, JSON_PRETTY_PRINT);

    return $json;
}

function renderDiff($diff)
{
    if (!$diff) {
        return null;
    }

    $renderedNodes = array_reduce($diff, function ($acc, $node) {
        [
            'key' => $key,
            'valueBefore' => $valueBefore,
            'valueAfter' => $valueAfter,
            'nodeType' => $type,
            'children' => $children,
        ] = $node;

        $acc[$key] = [
            'nodeType' => $type,
            'valueBefore' => $valueBefore,
            'valueAfter' => $valueAfter,
            'children' => renderDiff($children)
        ];

        return $acc;
    }, []);

    return $renderedNodes;
}
