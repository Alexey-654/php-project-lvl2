<?php

namespace GenDiff;

use function GenDiff\Parsers\parseFile;
use function GenDiff\DiffAst\makeDiffAst;
use function GenDiff\Formatters\Plain\toPlainFormat;
use function GenDiff\Formatters\Pretty\toPrettyFormat;
use function GenDiff\Formatters\Json\toJsonFormat;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    $fileContentBefore = file_get_contents($pathToFileBefore);
    $fileContentAfter = file_get_contents($pathToFileAfter);
    $mimeTypeBefore = mime_content_type($pathToFileBefore);
    $mimeTypeAfter = mime_content_type($pathToFileAfter);
    
    try {
        parseFile($fileContentBefore, $mimeTypeBefore);
        parseFile($fileContentAfter, $mimeTypeAfter);
    } catch (\Exception $e) {
        echo $e;
    }

    $itemsBefore = parseFile($fileContentBefore, $mimeTypeBefore);
    $itemsAfter = parseFile($fileContentAfter, $mimeTypeAfter);

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
