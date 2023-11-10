<?php

namespace App;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

$reader = ReaderEntityFactory::createReaderFromFile('lista.xlsx');
$reader->open('lista.xlsx');

$dados = [];
$headers = [];
foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $linha => $row) {
        $cells = $row->getCells();
        if ($linha == 6) {
            foreach ($cells as $key => $cell) {
                $headers[] = $cell->getValue();
            }
        }
        if ($linha > 5) {
            foreach ($cells as $key => $cell) {
                $dados[] = $cell->getValue();
            }
        }
    }
}

var_dump($headers);
// var_dump($dados);

$reader->close();