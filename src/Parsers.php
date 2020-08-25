<?php

namespace GenDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);
        case 'yaml':
            return Yaml::parse($data);
        default:
            throw new \Exception("Argument - '$format' is not valid");
    }
}
