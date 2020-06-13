<?php

namespace GenDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

use function GenDiff\GenDiff\genDiff;

function parse($pathToFile)
{
    $mimeType = mime_content_type($pathToFile);

    switch ($mimeType) {
        case 'application/json':
            $items = json_decode(file_get_contents($pathToFile), true);
            break;
        case 'text/plain':
            $items = Yaml::parseFile($pathToFile);
            break;
    }

    return $items;
}
