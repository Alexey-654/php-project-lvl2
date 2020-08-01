<?php

namespace GenDiff\Formatters\Plain;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;

const PREFIX = 'Property ';
const TEXT_NEW = ' was added with value: ';
const TEXT_DELETED = ' was removed';
const TEXT_CHANGED1 = ' was changed. From ';
const TEXT_CHANGED2 = ' to ';
const TEXT_NESTED = ' complex value ';

function toPlainFormat($diffTreeAst, $key = '')
{
    $renderedArray = array_reduce($diffTreeAst, function ($acc, $node) use ($key) {
        if (empty($key)) {
            $key = $node['key'];
        } else {
            $key = $key . "." . $node['key'];
        }

        if (is_array($node['value_before'])) {
            $valueBefore = "complex value";
        } else {
            $valueBefore = $node['value_before'];
        }
        if (is_array($node['value_after'])) {
            $valueAfter = "complex value";
        } else {
            $valueAfter = $node['value_after'];
        }

        switch ($node['node_type']) {
            case NEW_NODE:
                $acc[] = PREFIX . "'{$key}'" . TEXT_NEW . "'{$valueAfter}'";
                break;
            case DELETED_NODE:
                $acc[] = PREFIX . "'{$key}'" . TEXT_DELETED;
                break;
            case CHANGED_NODE:
                $acc[] = PREFIX . "'{$key}'" . TEXT_CHANGED1 . "'{$valueBefore}'" . TEXT_CHANGED2 . "'{$valueAfter}'";
                break;
            case NESTED_NODE:
                $acc[] = toPlainFormat($node['children'], $key);
                break;
        }

        return $acc;
    }, []);

    return implode("\n", $renderedArray);
}
