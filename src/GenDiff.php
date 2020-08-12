<?php

namespace GenDiff;

use function GenDiff\Parsers\parse;
use function GenDiff\Diff\makeDiff;
use function GenDiff\Formatters\Plain\toPlainFormat;
use function GenDiff\Formatters\Pretty\toPrettyFormat;
use function GenDiff\Formatters\Json\toJsonFormat;

function genDiff($pathToFileBefore, $pathToFileAfter, $outputFormat = 'pretty')
{
    $dataBefore = file_get_contents($pathToFileBefore);
    $dataAfter = file_get_contents($pathToFileAfter);
    $formatFileBefore = pathinfo($pathToFileBefore, PATHINFO_EXTENSION);
    $formatFileAfter = pathinfo($pathToFileAfter, PATHINFO_EXTENSION);
    
    $itemsBefore = parse($dataBefore, $formatFileBefore);
    $itemsAfter = parse($dataAfter, $formatFileAfter);

    $diff = makeDiff($itemsBefore, $itemsAfter);

    switch ($outputFormat) {
        case 'pretty':
            return toPrettyFormat($diff);
        case 'plain':
            return toPlainFormat($diff);
        case 'json':
            return toJsonFormat($diff);
        default:
            throw new \Exception("Argument '$outputFormat' is not valid");
    }
}
