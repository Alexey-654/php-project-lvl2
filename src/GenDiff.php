<?php

namespace GenDiff;

use function GenDiff\Parsers\parse;
use function GenDiff\DiffAst\makeDiffAst;
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

    $diffAst = makeDiffAst($itemsBefore, $itemsAfter);

    switch ($outputFormat) {
        case 'pretty':
            return toPrettyFormat($diffAst);
        case 'plain':
            return toPlainFormat($diffAst);
        case 'json':
            return toJsonFormat($diffAst);
        default:
            throw new \Exception("Argument '$outputFormat' is not valid for function 'genDiff'");
    }
}
