<?php

namespace GenDiff\GenDiff;

use function GenDiff\Parsers\parse;

function genDiff($pathToFile1, $pathToFile2)
{
    if (!isFilesExist($pathToFile1, $pathToFile2)) {
        return;
    }

    $items1 = parse($pathToFile1);
    $items2 = parse($pathToFile2);
    $result = [];

    foreach ($items1 as $key => $value) {
        if (array_key_exists($key, $items2) && in_array($value, $items2, true)) {
            $result[$key] = $value;
        }
        if (array_key_exists($key, $items2) && !in_array($value, $items2, true)) {
            $result['+ ' . $key] = $items2[$key];
            $result['- ' . $key] = $value;
        }
        if (!array_key_exists($key, $items2)) {
            $result['- ' . $key] = $value;
        }
    }
    foreach ($items2 as $key => $value) {
        if (!array_key_exists($key, $items1)) {
            $result['+ ' . $key] = $value;
        }
    }

    return (json_encode($result, JSON_PRETTY_PRINT));
}

function isFilesExist($pathToFile1, $pathToFile2)
{
    $ErrorMessage = "Error. Can't find file on this path -";

    if (!file_exists($pathToFile1)) {
        echo $ErrorMessage . $pathToFile1;
        return false;
    }
    if (!file_exists($pathToFile2)) {
        echo $ErrorMessage . $pathToFile1;
        return false;
    }
    return true;
}
