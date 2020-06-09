<?php

namespace GenDiff\GenDiff;

function genDiff($pathToFile1, $pathToFile2)
{
    $items1 = json_decode(file_get_contents($pathToFile1), true);
    $items2 = json_decode(file_get_contents($pathToFile2), true);
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
