<?php

namespace GenDiff\Formatters\Json;

function toJsonFormat($diff)
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
