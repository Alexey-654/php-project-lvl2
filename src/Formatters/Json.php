<?php

namespace GenDiff\Formatters\Json;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;

const NEW_PREFIX = '+ ';
const DEL_PREFIX = '- ';
const BLANK_PREFIX = '  ';

function toJsonFormat($diffAst)
{
    $renderedArray = renderAst($diffAst);
    $json = json_encode($renderedArray, JSON_PRETTY_PRINT);

    return $json;
}

function renderAst($diffAst)
{
    $renderedArray = array_reduce($diffAst, function ($acc, $node) {
        [
            'key' => $key,
            'value_before' => $valueBefore,
            'value_after' => $valueAfter,
            'children' => $children,
            'node_type' => $type,
        ] = $node;

        switch ($type) {
            case NEW_NODE:
                $acc[NEW_PREFIX . $key] = $valueAfter;
                break;
            case DELETED_NODE:
                $acc[DEL_PREFIX . $key] = $valueBefore;
                break;
            case UNCHANGED_NODE:
                $acc[BLANK_PREFIX . $key] = $valueBefore;
                break;
            case CHANGED_NODE:
                $acc[NEW_PREFIX . $key] = $valueAfter;
                $acc[DEL_PREFIX . $key] = $valueBefore;
                break;
            case NESTED_NODE:
                $acc[BLANK_PREFIX . $key] = renderAst($children);
                break;
            default:
                throw new \Exception("Type of node - '$type' is undefined");
        }

        return $acc;
    }, []);

    return $renderedArray;
}
