<?php

namespace GenDiff\Formatters\Pretty;

use const GenDiff\Diff\NEW_NODE;
use const GenDiff\Diff\DELETED_NODE;
use const GenDiff\Diff\UNCHANGED_NODE;
use const GenDiff\Diff\CHANGED_NODE;
use const GenDiff\Diff\NESTED_NODE;

const MIN_SPACE_COUNT = 2;
const SPACE_FOR_EACH_LEVEL_COUNT = 4;

const NEW_PREFIX = '+ ';
const DEL_PREFIX = '- ';
const BLANK_PREFIX = '  ';

function toPrettyFormat($diff)
{
    return "{\n" . renderDiff($diff) . "\n}";
}


function renderDiff($diff, $level = 0)
{
    $renderedNodes = array_map(fn ($node) => makeStringFromNode($node, $level), $diff);

    return implode("\n", $renderedNodes);
}


function makeStringFromNode($node, $level)
{
    $spaceCount = MIN_SPACE_COUNT + ($level * SPACE_FOR_EACH_LEVEL_COUNT);
    $indent = str_pad("", $spaceCount, " ", STR_PAD_LEFT);

    if (isset($node['valueBefore'])) {
        $valueBefore = getString($node['valueBefore'], $level);
    }
    if (isset($node['valueAfter'])) {
        $valueAfter = getString($node['valueAfter'], $level);
    }

    switch ($node['nodeType']) {
        case NESTED_NODE:
            $value = renderDiff($node['children'], $level + 1);
            return $indent . BLANK_PREFIX . $node['key'] . ": {\n" . $value  . "\n" . $indent . BLANK_PREFIX . "}";
        case NEW_NODE:
            return $indent . NEW_PREFIX . $node['key'] . ': ' . $valueAfter;
        case DELETED_NODE:
            return $indent . DEL_PREFIX . $node['key'] . ': ' . $valueBefore;
        case UNCHANGED_NODE:
            return $indent . BLANK_PREFIX . $node['key'] . ': ' . $valueAfter;
        case CHANGED_NODE:
            $string1 = $indent . NEW_PREFIX . $node['key'] . ': ' . $valueAfter;
            $string2 = $indent . DEL_PREFIX . $node['key'] . ': ' . $valueBefore;
            return $string1 . "\n" . $string2;
        default:
            throw new \Exception("Node - '{$node['nodeType']}' is undefined");
    }
}


function getString($value, $level)
{
    return is_array($value) ? makeStringfromArray($value, $level + 1) : boolToString($value);
}


function makeStringfromArray($value, $level)
{
    $spaceCountCurrentLevel = MIN_SPACE_COUNT + ($level * SPACE_FOR_EACH_LEVEL_COUNT);
    $spaceCountPriorLevel = MIN_SPACE_COUNT + (($level - 1) * SPACE_FOR_EACH_LEVEL_COUNT);
    $indentCurrentLevel = str_pad("", $spaceCountCurrentLevel, " ", STR_PAD_LEFT);
    $indentPriorLevel = str_pad("", $spaceCountPriorLevel, " ", STR_PAD_LEFT);

    $keys = array_keys($value);
    
    $mappedValues = array_map(function ($key, $value) use ($level, $indentCurrentLevel, $indentPriorLevel) {
        $value = getString($value, $level);
        return $indentCurrentLevel . BLANK_PREFIX . $key . ': ' . $value;
    }, $keys, $value);

    $string = implode("\n", $mappedValues);
    return "{\n" . $string . "\n" . $indentPriorLevel . BLANK_PREFIX . "}";
}


function boolToString($data)
{
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    
    return $data;
}
