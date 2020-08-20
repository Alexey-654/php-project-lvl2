<?php

namespace GenDiff\Diff;

const NEW_NODE = 'new';
const DELETED_NODE = 'deleted';
const UNCHANGED_NODE = 'unchanged';
const CHANGED_NODE = 'changed';
const NESTED_NODE = 'nested';

function makeDiff($itemsBefore, $itemsAfter)
{
    $allKeys = getAllUniqueKeys($itemsBefore, $itemsAfter);
    $diff = array_map(function ($key) use ($itemsBefore, $itemsAfter) {
        return makeNode($key, $itemsBefore, $itemsAfter);
    }, $allKeys);

    return $diff;
}


function makeNode($key, $itemsBefore, $itemsAfter)
{
    if (!array_key_exists($key, $itemsBefore)) {
        return [
            'key' => $key,
            'valueBefore' => null,
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => NEW_NODE,
            'children' => null
        ];
    }

    if (!array_key_exists($key, $itemsAfter)) {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => null,
            'nodeType' => DELETED_NODE,
            'children' => null
        ];
    }

    if (is_array($itemsBefore[$key]) && is_array($itemsAfter[$key])) {
        $children = makeDiff($itemsBefore[$key], $itemsAfter[$key]);

        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => NESTED_NODE,
            'children' => $children
        ];
    }

    if ($itemsBefore[$key] === $itemsAfter[$key]) {
        return [
            'key' => $key,
            'valueBefore' => $itemsAfter[$key],
            'valueAfter' => $itemsBefore[$key],
            'nodeType' => UNCHANGED_NODE,
            'children' => null
        ];
    } else {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => CHANGED_NODE,
            'children' => null
        ];
    }
}


function getAllUniqueKeys($items1, $items2)
{
    $keys1 = array_keys($items1);
    $keys2 = array_keys($items2);

    return  array_unique(array_merge($keys1, $keys2));
}
