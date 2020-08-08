<?php

namespace GenDiff\Formatters\Pretty;

use const GenDiff\DiffAst\NEW_NODE;
use const GenDiff\DiffAst\DELETED_NODE;
use const GenDiff\DiffAst\UNCHANGED_NODE;
use const GenDiff\DiffAst\CHANGED_NODE;
use const GenDiff\DiffAst\NESTED_NODE;

const MIN_SPACE_COUNT = 2;
const SPACE_FOR_EACH_LEVEL_COUNT = 4;

const NEW_PREFIX = '+ ';
const DEL_PREFIX = '- ';
const BLANK_PREFIX = '  ';

function toPrettyFormat($diffAst)
{
    return "{\n" . renderAst($diffAst) . "\n}";
}


function renderAst($diffAst, $level = 0)
{
    $renderedArray = array_reduce($diffAst, function ($acc, $node) use ($level) {
        [
            'key' => $key,
            'value_before' => $valueBefore,
            'value_after' => $valueAfter,
            'children' => $children,
            'node_type' => $type,
        ] = $node;

        switch ($type) {
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
                $valueChildren = renderAst($children, $nestedlevel);
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
    $spaceQuantity = MIN_SPACE_COUNT + ($level * SPACE_FOR_EACH_LEVEL_COUNT);
    $spaceString = str_pad("", $spaceQuantity, " ", STR_PAD_LEFT);

    if ($prefix === NESTED_NODE) {
        $resultString = $spaceString . BLANK_PREFIX . $key . ": {\n" . toString($value) . "\n" . $spaceString . "  }";

        return $resultString;
    }
    if (is_array($value)) {
        $level++;
        $mappedValues = array_map(function ($key, $value) use ($level) {
            return makeString($key, $value, $level, BLANK_PREFIX);
        }, array_keys($value), $value);
        $value = implode("\n", $mappedValues);
        $resultString = $spaceString . $prefix . $key . ": {\n" . toString($value) . "\n" . $spaceString . "  }";

        return $resultString;
    }

    $resultString =  $spaceString . $prefix . $key . ': ' . toString($value);
    
    return $resultString;
}


function toString($data)
{
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    
    return $data;
}
