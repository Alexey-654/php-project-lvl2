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
    $renderedArray = array_reduce($diff, function ($acc, $node) use ($level) {
        [
            'key' => $key,
            'valueBefore' => $valueBefore,
            'valueAfter' => $valueAfter,
            'nodeType' => $nodeType,
            'children' => $children,
        ] = $node;

        switch ($nodeType) {
            case NEW_NODE:
                $acc[] = makeString($key, $valueAfter, $level, NEW_PREFIX);
                break;
            case DELETED_NODE:
                $acc[] = makeString($key, $valueBefore, $level, DEL_PREFIX);
                break;
            case UNCHANGED_NODE:
                $acc[] = makeString($key, $valueBefore, $level, BLANK_PREFIX);
                break;
            case CHANGED_NODE:
                $acc[] = makeString($key, $valueAfter, $level, NEW_PREFIX);
                $acc[] = makeString($key, $valueBefore, $level, DEL_PREFIX);
                break;
            case NESTED_NODE:
                $nestedlevel = $level + 1;
                $valueChildren = renderDiff($children, $nestedlevel);
                $acc[] = makeString($key, $valueChildren, $level, NESTED_NODE);
                break;
            default:
                throw new \Exception("Type of node - '$type' is undefined");
        }

        return $acc;
    }, []);

    return implode("\n", $renderedArray);
}


function makeString($key, $value, $level, $prefix)
{
    $spaceCount = MIN_SPACE_COUNT + ($level * SPACE_FOR_EACH_LEVEL_COUNT);
    $indent = str_pad("", $spaceCount, " ", STR_PAD_LEFT);

    if ($prefix === NESTED_NODE) {
        $resultString = $indent . BLANK_PREFIX . $key . ": {\n" . toString($value) . "\n" . $indent . "  }";
        return $resultString;
    }

    if (is_array($value)) {
        $level++;

        $keys = array_keys($value);
        $mappedValues = array_map(function ($key, $value) use ($level) {
            return makeString($key, $value, $level, BLANK_PREFIX);
        }, $keys, $value);

        $value = implode("\n", $mappedValues);
        $resultString = $indent . $prefix . $key . ": {\n" . toString($value) . "\n" . $indent . "  }";
        return $resultString;
    }

    $resultString =  $indent . $prefix . $key . ': ' . toString($value);
    
    return $resultString;
}


function toString($data)
{
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    
    return $data;
}
