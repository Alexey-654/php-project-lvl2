<?php

namespace GenDiff\Formatters\Plain;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;

const PREFIX = 'Property ';
const TEXT_NEW_NODE = ' was added with value: ';
const TEXT_DELETED_NODE = ' was removed';
const TEXT_CHANGED_NODE1 = ' was changed. From ';
const TEXT_CHANGED_NODE2 = ' to ';
const TEXT_COMPLEX = 'complex value';

function toPlainFormat($diffTreeAst, $key = '')
{
    $renderedArray = array_reduce($diffTreeAst, function ($acc, $node) use ($key) {
        $key = empty($key) ? $node['key'] : $key . "." . $node['key'];
        $valueBefore = is_array($node['value_before']) ? TEXT_COMPLEX : $node['value_before'];
        $valueAfter = is_array($node['value_after']) ? TEXT_COMPLEX : $node['value_after'];

        switch ($node['node_type']) {
            case NEW_NODE:
                $acc[] = PREFIX . "'$key'" . TEXT_NEW_NODE . "'$valueAfter'";
                break;
            case DELETED_NODE:
                $acc[] = PREFIX . "'$key'" . TEXT_DELETED_NODE;
                break;
            case CHANGED_NODE:
                $acc[] = PREFIX . "'$key'" . TEXT_CHANGED_NODE1 . "'$valueBefore'"
                         . TEXT_CHANGED_NODE2 . "'$valueAfter'";
                break;
            case NESTED_NODE:
                $acc[] = toPlainFormat($node['children'], $key);
                break;
        }

        return $acc;
    }, []);

    return implode("\n", $renderedArray);
}
