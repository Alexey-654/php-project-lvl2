<?php

namespace GenDiff\ToString;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;

const PREFIX_NEW = '+ ';
const PREFIX_DEL = '- ';
const PREFIX_UNCHANGED = '  ';


function toString($difTreeAst)
{
    $renderedArray = renderAst($difTreeAst);
    $json = json_encode($renderedArray, JSON_PRETTY_PRINT);
    $needles = ['"', ','];
    $renderedJson = str_replace($needles, '', $json);

    return $renderedJson;
}

function renderAst($difTree)
{
    $renderedArray = array_reduce($difTree, function ($acc, $item) {
        switch ($item['node type']) {
            case NEW_NODE:
                $acc[PREFIX_NEW . $item['key']] = $item['valueAfter'];
                break;
            case DELETED_NODE:
                $acc[PREFIX_DEL . $item['key']] = $item['valueBefore'];
                break;
            case UNCHANGED_NODE:
                $acc[PREFIX_UNCHANGED . $item['key']] = $item['valueBefore'];
                break;
            case NESTED_NODE:
                $acc[PREFIX_UNCHANGED . $item['key']] = renderAst($item['children']);
                break;
        }

        return $acc;
    }, []);

    return $renderedArray;
}
