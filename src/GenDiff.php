<?php

namespace GenDiff;

use function GenDiff\Parsers\parseFile;
use function GenDiff\DiffAst\makeDiffAst;
use function GenDiff\ToString\toString;

function genDiff($pathToFileBefore, $pathToFileAfter)
{
    $itemsBefore = parseFile($pathToFileBefore);
    $itemsAfter = parseFile($pathToFileAfter);
    $difTreeAst = makeDiffAst($itemsBefore, $itemsAfter);

    return toString($difTreeAst);
}
