<?php

namespace GenDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile($fileContent, $mimeType)
{
    switch ($mimeType) {
        case 'application/json':
            return json_decode($fileContent, true);
            break;
        case 'text/plain':
            // return Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);
            return Yaml::parse($fileContent);
            break;
        default:
            throw new \Exception("Mime type - '$mimeType' of input file is not valid");
            break;
    }
}
