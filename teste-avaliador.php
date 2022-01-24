<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require  'vendor/autoload.php';

// Cria o cenário para o teste (Arrange - Given)
$leilao = new Leilao('Fiat 137 0KM');

$maria = new Usuario('Maria');
$joao = new Usuario('João');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

// Executo o código a ser testado (Act - When)
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

// Verifico se a saída é a esperada (Assert - Then)
$valorEsperado = 2500;

if ($valorEsperado == $maiorValor) {
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}


