<?php

namespace GenDiff;

use function GenDiff\Parsers\parseFile;
use function GenDiff\DiffAst\makeDiffAst;
use function GenDiff\Formatters\Plain\toPlainFormat;
use function GenDiff\Formatters\Pretty\toPrettyFormat;
use function GenDiff\Formatters\Json\toJsonFormat;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    try {
        parseFile($pathToFileBefore);
        parseFile($pathToFileAfter);
    } catch (\Exception $e) {
        echo $e;
    }

    $itemsBefore = parseFile($pathToFileBefore);
    $itemsAfter = parseFile($pathToFileAfter);
    $diffTreeAst = makeDiffAst($itemsBefore, $itemsAfter);
    switch ($format) {
        case 'pretty':
            return toPrettyFormat($diffTreeAst);
            break;
        case 'plain':
            return toPlainFormat($diffTreeAst);
            break;
        case 'json':
            return toJsonFormat($diffTreeAst);
            break;
        default:
            return "Format '$format' is not valid!";
            break;
    }
}
