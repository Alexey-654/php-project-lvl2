<?php

namespace GenDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile($pathToFile)
{
    $mimeType = mime_content_type($pathToFile);
    switch ($mimeType) {
        case 'application/json':
            $items = json_decode(file_get_contents($pathToFile), true);
            break;
        case 'text/plain':
            // $items = Yaml::parseFile($pathToFile, Yaml::PARSE_OBJECT_FOR_MAP);
            $items = Yaml::parseFile($pathToFile);
            break;
        default:
            throw new \Exception("Type of file '$pathToFile' is not valid");
            break;
    }
    
    return $items;
}
