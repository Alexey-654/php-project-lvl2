<?php

namespace GenDiff\Formatters\Json;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;

const PREFIX_NEW = '+ ';
const PREFIX_DEL = '- ';
const PREFIX_UNCHANGED = '  ';


function toJsonFormat($difTreeAst)
{
    $renderedArray = renderAst($difTreeAst);
    $json = json_encode($renderedArray, JSON_PRETTY_PRINT);

    return $json;
}

function renderAst($difTree)
{
    $renderedArray = array_reduce($difTree, function ($acc, $node) {
        switch ($node['node_type']) {
            case NEW_NODE:
                $acc[PREFIX_NEW . $node['key']] = $node['value_after'];
                break;
            case DELETED_NODE:
                $acc[PREFIX_DEL . $node['key']] = $node['value_before'];
                break;
            case UNCHANGED_NODE:
                $acc[PREFIX_UNCHANGED . $node['key']] = $node['value_before'];
                break;
            case CHANGED_NODE:
                $acc[PREFIX_NEW . $node['key']] = $node['value_after'];
                $acc[PREFIX_DEL . $node['key']] = $node['value_before'];
                break;
            case NESTED_NODE:
                $acc[PREFIX_UNCHANGED . $node['key']] = renderAst($node['children']);
                break;
        }

        return $acc;
    }, []);

    return $renderedArray;
}
