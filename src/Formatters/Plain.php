<?php

namespace GenDiff\Formatters\Plain;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;

function toPlainFormat($diffAst, $key = '')
{
    $renderedArray = array_reduce($diffAst, function ($acc, $node) use ($key) {
        $key = empty($key) ? $node['key'] : "{$key}.{$node['key']}";
        $valueBefore = is_array($node['value_before']) ? 'complex value' : $node['value_before'];
        $valueAfter = is_array($node['value_after']) ? 'complex value' : $node['value_after'];

        switch ($node['node_type']) {
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
                throw new \Exception("Node - '{$node['node_type']}' is undefined");
        }

        return $acc;
    }, []);

    return implode("\n", $renderedArray);
}
