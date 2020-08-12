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
    $renderedArray = renderDiff($diff);
    $json = json_encode($renderedArray, JSON_PRETTY_PRINT);

    return $json;
}

function renderDiff($diff)
{
    $renderedArray = array_reduce($diff, function ($acc, $node) {
        [
            'key' => $key,
            'valueBefore' => $valueBefore,
            'valueAfter' => $valueAfter,
            'children' => $children,
            'nodeType' => $type,
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
                $acc[BLANK_PREFIX . $key] = renderDiff($children);
                break;
            default:
                throw new \Exception("Type of node - '$type' is undefined");
        }

        return $acc;
    }, []);

    return $renderedArray;
}
