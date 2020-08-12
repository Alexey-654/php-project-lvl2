<?php

namespace GenDiff\Formatters\Plain;

use const GenDiff\Diff\NEW_NODE;
use const GenDiff\Diff\DELETED_NODE;
use const GenDiff\Diff\UNCHANGED_NODE;
use const GenDiff\Diff\NESTED_NODE;
use const GenDiff\Diff\CHANGED_NODE;

function toPlainFormat($diff, $key = '')
{
    $renderedArray = array_reduce($diff, function ($acc, $node) use ($key) {
        $key = empty($key) ? $node['key'] : "{$key}.{$node['key']}";
        $valueBefore = is_array($node['valueBefore']) ? 'complex value' : $node['valueBefore'];
        $valueAfter = is_array($node['valueAfter']) ? 'complex value' : $node['valueAfter'];

        switch ($node['nodeType']) {
            case NEW_NODE:
                $acc[] = "Property '{$key}' was added with value: '{$valueAfter}'";
                break;
            case DELETED_NODE:
                $acc[] = "Property '{$key}' was removed";
                break;
            case CHANGED_NODE:
                $acc[] = "Property '{$key}' was changed. From '{$valueBefore}' to '{$valueAfter}'";
                break;
            case NESTED_NODE:
                $acc[] = toPlainFormat($node['children'], $key);
                break;
            case UNCHANGED_NODE:
                break;
            default:
                throw new \Exception("Node - '{$node['nodeType']}' is undefined");
        }

        return $acc;
    }, []);

    return implode("\n", $renderedArray);
}
