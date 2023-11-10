<?php

namespace PhpPiaui\Sorteio;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

const INICIO_LISTA = 6;
const FINAL_LISTA = 183;

$reader = ReaderEntityFactory::createReaderFromFile('lista.xlsx');
$reader->open('lista.xlsx');

$lista = [];
$headers = [];
foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $linha => $row) {
        $cells = $row->getCells();
        foreach ($cells as $key => $cell) {
            if (str_contains($cell, 'xportado')) {
                break;
            }
            if ($linha == INICIO_LISTA) {
                $headers[] = $cell->getValue();
            }
            if ($linha > INICIO_LISTA) {
                $itensDaLinha[$key] = $cell->getValue();
            }
        }
        if ($linha > INICIO_LISTA && $linha < FINAL_LISTA) {
            $novoItem = new Item($headers, $itensDaLinha);
            array_push($lista, $novoItem->getItem());
        }
    }
}
$reader->close();

$deve_fazer_sorteio = false;

if ($_GET['sorteio']) {
    $deve_fazer_sorteio = true;

    $id_sorteado = rand(INICIO_LISTA, FINAL_LISTA);
}

function createSlug($str, $delimiter = '_')
{
    return strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
}

class Item
{
    private array $item;
    public function __construct($headers, $itensDaLinha)
    {
        $this->setItem($headers, $itensDaLinha);
    }

    public function setItem($headers, $itensDaLinha)
    {
        $this->item = [
            createSlug($headers[0]) => $itensDaLinha[0],
            createSlug($headers[1]) => $itensDaLinha[1],
            createSlug($headers[2]) => $itensDaLinha[2],
            createSlug($headers[3]) => $itensDaLinha[3],
            createSlug($headers[4]) => $itensDaLinha[4],
            createSlug($headers[5]) => $itensDaLinha[5],
            createSlug($headers[6]) => $itensDaLinha[6],
            createSlug($headers[7]) => $itensDaLinha[7],
            createSlug($headers[8]) => $itensDaLinha[8],
            createSlug($headers[9]) => $itensDaLinha[9],
            createSlug($headers[10]) => $itensDaLinha[10],
            createSlug($headers[11]) => $itensDaLinha[11],
            createSlug($headers[12]) => $itensDaLinha[12],
            createSlug($headers[13]) => $itensDaLinha[13],
            createSlug($headers[14]) => $itensDaLinha[14],
            createSlug($headers[15]) => $itensDaLinha[15],
            createSlug($headers[16]) => $itensDaLinha[16],
            createSlug($headers[17]) => $itensDaLinha[17],
            createSlug($headers[18]) => $itensDaLinha[18],
            createSlug($headers[19]) => $itensDaLinha[19],
            createSlug($headers[20]) => $itensDaLinha[20],
        ];
    }

    public function getItem()
    {
        return $this->item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteio PHPi</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Demo styles -->
    <style>
        body {
            position: relative;
        }

        body {
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .swiper {
            width: 100%;
            height: 65%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 65%;
        }
    </style>
</head>

<body class="bg-base-100 h-screen">
    <div class="flex justify-center flex-col">
        <div class="flex flex-col text-center m-4">
            <h1 class="text-4xl">Sorteio PHPi</h1>
            <h2 class="text-2xl"><?php echo count($lista) ?> participantes</h2>
        </div>
        <div class="flex flex-col text-center">
            <div class="flex justify-center flex-col">
                <div class="m-2">
                    <a href="/?sorteio=true" class="btn btn-primary">
                        <?php echo $deve_fazer_sorteio ? "Sortear Novamente" : "Iniciar Sorteio" ?>
                    </a>
                </div>
                <div class="">
                    <?php 
                        echo !$deve_fazer_sorteio 
                        ? "" 
                        : "<a href='/' class='btn btn-outline btn-sm'>Voltar</a>"
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="<?php echo $deve_fazer_sorteio ? "" : "hidden" ?> swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <?php
                    echo 'Ingresso nº: ' . $lista[$id_sorteado]['ingresso'] . ' <br> ';
                    echo 'Data da compra: ' . $lista[$id_sorteado]['data_compra'] . ' <br> ';
                ?>
            </div>
            <div class="swiper-slide">
                <?php
                    echo $lista[$id_sorteado]['nome'] . ' ' . $lista[$id_sorteado]['sobrenome'];
                ?>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            direction: "vertical",
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>

</html>