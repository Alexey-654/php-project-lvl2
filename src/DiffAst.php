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
            $acc[] = makeNode($key, $itemsBefore, $itemsAfter, CHANGED_NODE);
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
    switch ($type) {
        case NEW_NODE:
            $node = [
                'key' => $key,
                'value_before' => '',
                'value_after' => $itemsAfter[$key],
                'node_type' => NEW_NODE,
                'children' => null
            ];
            break;
        case DELETED_NODE:
            $node = [
                'key' => $key,
                'value_before' => $itemsBefore[$key],
                'value_after' => '',
                'node_type' => DELETED_NODE,
                'children' => null
            ];
            break;
        case UNCHANGED_NODE:
            $node = [
                'key' => $key,
                'value_before' => $itemsAfter[$key],
                'value_after' => $itemsBefore[$key],
                'node_type' => UNCHANGED_NODE,
                'children' => null
            ];
            break;
        case CHANGED_NODE:
            $node = [
                'key' => $key,
                'value_before' => $itemsBefore[$key],
                'value_after' => $itemsAfter[$key],
                'node_type' => CHANGED_NODE,
                'children' => null
            ];
            break;
        case NESTED_NODE:
            $node = [
                'key' => $key,
                'value_before' => $itemsBefore[$key],
                'value_after' => $itemsAfter[$key],
                'node_type' => NESTED_NODE,
                'children' => $children
            ];
            break;
        default:
            throw new \Exception("Type of node - '$type' is undefined");
    }

    return $node;
}

function isNew($key, $itemsBefore)
{
    return !array_key_exists($key, $itemsBefore);
}

function isDeleted($key, $itemsAfter)
{
    return !array_key_exists($key, $itemsAfter);
}

function isUnchanged($key, $itemsBefore, $itemsAfter)
{
    return (
        isset($itemsBefore[$key], $itemsAfter[$key])
        && !is_array($itemsBefore[$key])
        && !is_array($itemsAfter[$key])
        && in_array($itemsBefore[$key], $itemsAfter, true)
    );
}

function isChanged($key, $itemsBefore, $itemsAfter)
{
    return (
        isset($itemsBefore[$key], $itemsAfter[$key])
        && !is_array($itemsBefore[$key])
        && !is_array($itemsAfter[$key])
        && !in_array($itemsBefore[$key], $itemsAfter, true)
    );
}

function isNested($key, $itemsBefore, $itemsAfter)
{
    return (
        isset($itemsBefore[$key], $itemsAfter[$key])
        && is_array($itemsBefore[$key])
        && is_array($itemsAfter[$key])
    );
}
