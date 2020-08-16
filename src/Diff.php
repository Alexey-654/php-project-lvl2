<?php

namespace GenDiff\Diff;

const NEW_NODE = 'new';
const DELETED_NODE = 'deleted';
const UNCHANGED_NODE = 'unchanged';
const CHANGED_NODE = 'changed';
const NESTED_NODE = 'nested';

function makeDiff($itemsBefore, $itemsAfter)
{
    $allKeys = array_unique(array_merge(array_keys($itemsBefore), array_keys($itemsAfter)));

    $diff = array_reduce($allKeys, function ($acc, $key) use ($itemsBefore, $itemsAfter) {
        $children = getChildren($key, $itemsBefore, $itemsAfter);
        $acc[] = makeNode($key, $itemsBefore, $itemsAfter, $children);

        return $acc;
    }, []);

    return $diff;
}

function getChildren($key, $itemsBefore, $itemsAfter)
{
    if (
        isset($itemsBefore[$key], $itemsAfter[$key])
        && is_array($itemsBefore[$key])
        && is_array($itemsAfter[$key])
    ) {
        $children = makeDiff($itemsBefore[$key], $itemsAfter[$key]);
    }
    return $children ?? null;
}

function makeNode($key, $itemsBefore, $itemsAfter, $children = null)
{
    if ($children) {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => NESTED_NODE,
            'children' => $children
        ];
    }

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

    if (in_array($itemsBefore[$key], $itemsAfter, true)) {
        return [
            'key' => $key,
            'valueBefore' => $itemsAfter[$key],
            'valueAfter' => $itemsBefore[$key],
            'nodeType' => UNCHANGED_NODE,
            'children' => null
        ];
    }

    if (!in_array($itemsBefore[$key], $itemsAfter, true)) {
        return [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'nodeType' => CHANGED_NODE,
            'children' => null
        ];
    }
}
