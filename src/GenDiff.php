<?php

namespace GenDiff;

use function GenDiff\Parsers\parseFile;
use function GenDiff\DiffAst\makeDiffAst;
use function GenDiff\Formatters\Plain\toPlainFormat;
use function GenDiff\Formatters\Pretty\toPrettyFormat;

function genDiff($pathToFileBefore, $pathToFileAfter, $format)
{
    $itemsBefore = parseFile($pathToFileBefore);
    $itemsAfter = parseFile($pathToFileAfter);
    $diffTreeAst = makeDiffAst($itemsBefore, $itemsAfter);
    if ($format === 'plain') {
        return toPlainFormat($diffTreeAst);
    } else {
        return toPrettyFormat($diffTreeAst);
    }
}
