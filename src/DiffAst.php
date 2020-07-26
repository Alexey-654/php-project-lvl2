<?php

namespace GenDiff\DiffAst;

const NEW_NODE = 'new';
const DELETED_NODE = 'deleted';
const UNCHANGED_NODE = 'unchanged';
const CHANGED_NODE = 'changed';
const NESTED_NODE = 'nested';

function makeDiffAst($itemsBefore, $itemsAfter)
{
    $allKeys = array_unique(array_merge(array_keys($itemsBefore), array_keys($itemsAfter)));

    $diffAst = array_reduce($allKeys, function ($acc, $key) use ($itemsBefore, $itemsAfter) {
        if (isNew($key, $itemsBefore)) {
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, NEW_NODE);
        }
        if (isDeleted($key, $itemsAfter)) {
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, DELETED_NODE);
        }
        if (isUnchanged($key, $itemsBefore, $itemsAfter)) {
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, UNCHANGED_NODE);
        }
        if (isChanged($key, $itemsBefore, $itemsAfter)) {
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, NEW_NODE);
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, DELETED_NODE);
        }
        if (isNested($key, $itemsBefore, $itemsAfter)) {
            $children = makeDiffAst($itemsBefore[$key], $itemsAfter[$key]);
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, NESTED_NODE, $children);
        }

        return $acc;
    }, []);

    return $diffAst;
}

function makeNode($key, $itemsBefore, $itemsAfter, $type, $children = null)
{
    if ($type === NEW_NODE) {
        $node = [
            'key' => $key,
            'valueBefore' => '',
            'valueAfter' => $itemsAfter[$key],
            'node type' => NEW_NODE,
            'has children' => false
        ];
    }
    if ($type === DELETED_NODE) {
        $node = [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => '',
            'node type' => DELETED_NODE,
            'has children' => false
        ];
    }
    if ($type === UNCHANGED_NODE) {
        $node = [
            'key' => $key,
            'valueBefore' => $itemsAfter[$key],
            'valueAfter' => $itemsBefore[$key],
            'node type' => UNCHANGED_NODE,
            'has children' => false
        ];
    }
    if ($type === NESTED_NODE) {
        $node = [
            'key' => $key,
            'valueBefore' => $itemsBefore[$key],
            'valueAfter' => $itemsAfter[$key],
            'node type' => NESTED_NODE,
            'has children' => true,
            'children' => $children
        ];
    }

    return $node;
}

function isUnchanged($key, $itemsBefore, $itemsAfter)
{
    if (
        array_key_exists($key, $itemsBefore)
        && array_key_exists($key, $itemsAfter)
        && isset($itemsBefore[$key], $itemsAfter[$key])
        && !is_array($itemsBefore[$key])
        && !is_array($itemsAfter[$key])
    ) {
        return in_array($itemsBefore[$key], $itemsAfter, true);
    }

    return false;
}

function isChanged($key, $itemsBefore, $itemsAfter)
{
    if (
        array_key_exists($key, $itemsBefore)
        && array_key_exists($key, $itemsAfter)
        && isset($itemsBefore[$key], $itemsAfter[$key])
        && !is_array($itemsBefore[$key])
        && !is_array($itemsAfter[$key])
    ) {
        return !in_array($itemsBefore[$key], $itemsAfter, true);
    }

    return false;
}

function isNested($key, $itemsBefore, $itemsAfter)
{
    if (
        array_key_exists($key, $itemsBefore)
        && array_key_exists($key, $itemsAfter)
        && isset($itemsBefore[$key], $itemsAfter[$key])
        && is_array($itemsBefore[$key])
        && is_array($itemsAfter[$key])
    ) {
        return true;
    }

    return false;
}

function isNew($key, $itemsBefore)
{
    if (!array_key_exists($key, $itemsBefore)) {
        return true;
    }

    return false;
}

function isDeleted($key, $itemsAfter)
{
    if (!array_key_exists($key, $itemsAfter)) {
        return true;
    }

    return false;
}
