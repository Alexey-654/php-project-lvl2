<?php

namespace GenDiff\Formatters\Plain;

use const GenDiff\Diff\NEW_NODE;
use const GenDiff\Diff\DELETED_NODE;
use const GenDiff\Diff\UNCHANGED_NODE;
use const GenDiff\Diff\NESTED_NODE;
use const GenDiff\Diff\CHANGED_NODE;

function toPlainFormat($diff)
{
    $iter = function ($diff, $key = null) use (&$iter) {
        $filteredNodes = array_filter($diff, fn($node) => $node['nodeType'] !== UNCHANGED_NODE);

        $renderedNodes = array_map(function ($node) use (&$iter, $key) {
            $key = empty($key) ? $node['key'] : "{$key}.{$node['key']}";

            if (isset($node['valueBefore'])) {
                $valueBefore = getValue($node['valueBefore']);
            }
            if (isset($node['valueAfter'])) {
                $valueAfter = getValue($node['valueAfter']);
            }

            switch ($node['nodeType']) {
                case NEW_NODE:
                    return "Property '{$key}' was added with value: '{$valueAfter}'";
                case DELETED_NODE:
                    return "Property '{$key}' was removed";
                case CHANGED_NODE:
                    return "Property '{$key}' was changed. From '{$valueBefore}' to '{$valueAfter}'";
                case NESTED_NODE:
                    return $iter($node['children'], $key);
                default:
                    throw new \Exception("Node - '{$node['nodeType']}' is undefined");
            }
        }, $filteredNodes);
    
        return implode("\n", $renderedNodes);
    };

    return $iter($diff);
}

function getValue($value)
{
    return is_array($value) ? 'complex value' : $value;
}
