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
    $diff = array_map(fn ($key) => makeNode($key, $itemsBefore, $itemsAfter), $allKeys);

    return $diff;
}


function makeNode($key, $itemsBefore, $itemsAfter)
{
    if (!array_key_exists($key, $itemsBefore)) {
        return [
            'key' => $key,
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => NEW_NODE,
        ];
    }

    if (!array_key_exists($key, $itemsAfter)) {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'nodeType' => DELETED_NODE,
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
        ];
    } else {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => CHANGED_NODE,
        ];
    }
}


function getAllUniqueKeys($items1, $items2)
{
    $keys1 = array_keys($items1);
    $keys2 = array_keys($items2);

    return  array_unique(array_merge($keys1, $keys2));
}
