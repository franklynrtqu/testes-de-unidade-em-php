<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    /** @var Avaliador */
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }
    
    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEcontrarOMaiorValorDeLances(Leilao $leilao)
    {
        // Executo o código a ser testado (Act - When)
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Verifico se a saída é a esperada (Assert - Then)
        self::assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEcontrarOMenorValorDeLances(Leilao $leilao)
    {
        // Executo o código a ser testado (Act - When)
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        // Verifico se a saída é a esperada (Assert - Then)
        self::assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEcontrarOMenorValorDeLancesEmOrdemCrescente(Leilao $leilao)
    {
        // Executo o código a ser testado (Act - When)
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        // Verifico se a saída é a esperada (Assert - Then)
        self::assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvalidorDeveBuscar3MaioresValores(Leilao $leilao)
    {
       $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();

        static::assertCount(3, $maiores);
        static::assertEquals(2500, $maiores[0]->getValor());
        static::assertEquals(2000, $maiores[1]->getValor());
        static::assertEquals(1700, $maiores[2]->getValor());

    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio.');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já está está finalizado.');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public function testeLeilaoVazioNaoPodeSerFinalizado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão vazio não pode ser finalizado.');

        $leilao = new Leilao('Fiat Prêmio 0KM');
        $leilao->finaliza();
    }

    /* ------ DADOS ------- */
    public function leilaoEmOrdemCrescente()
    {
        // Cria o cenário para o teste (Arrange - Given)
        $leilao = new Leilao('Fiat 137 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente()
    {
        // Cria o cenário para o teste (Arrange - Given)
        $leilao = new Leilao('Fiat 137 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria()
    {
        // Cria o cenário para o teste (Arrange - Given)
        $leilao = new Leilao('Fiat 137 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }

}