<?php
namespace Alura\Leilao\Tests\Feature\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase{
    private static \PDO $pdo;

    public static function setUpBeforeClass(): void{
        $sql = "CREATE TABLE leiloes (
            id INTEGER PRIMARY KEY,
            descricao TEXT,
            finalizado BOOL,
            dataInicio TEXT
        )";
        self::$pdo = new \PDO('sqlite::memory:'); // ConnectionCreator::getConnection();
        self::$pdo->exec($sql);
    }

    protected function setUp(): void{
        
        self::$pdo->beginTransaction();
    }

    protected function tearDown(): void{
        self::$pdo->rollBack();
    }

    /**
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesNaoFinalizados(array $leiloes)
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame(
            'Variante 0Km',
            $leiloes[0]->recuperarDescricao()
        );
        self::assertFalse($leiloes[0]->estaFinalizado());
    }

    /**
     * @dataProvider leiloes
    */
    public function testBuscaLeiloesFinalizados(array $leiloes){
        // arrange
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // assert
        $leiloes = $leilaoDao->recuperarFinalizados();
        // assert
        self::assertCount(1, $leiloes);
        self::assertEquals('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
    }

    public function testAoAtualizarLeilaoStatusDeveSerAlterado(){
        $leilao = new Leilao('Brasilia Amarela');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilaoSalvo = $leilaoDao->salva($leilao);
        
        $leiloesNaoFinalizados = $leilaoDao->recuperarNaoFinalizados();
        self::assertCount(1, $leiloesNaoFinalizados);
        self::assertEquals('Brasilia Amarela', $leiloesNaoFinalizados[0]->recuperarDescricao());
        self::assertFalse($leiloesNaoFinalizados[0]->estaFinalizado());

        $leilaoSalvo->finaliza();
        $leilaoDao->atualiza($leilaoSalvo);

        $leiloesFinalizados = $leilaoDao->recuperarFinalizados();
        self::assertCount(1, $leiloesFinalizados);
        self::assertEquals('Brasilia Amarela', $leiloesFinalizados[0]->recuperarDescricao());
        self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
    }


    /**
     *  Função do data provider
     */
    public static function leiloes()
    {
        $naoFinalizado = new Leilao('Variante 0Km');
        $finalizado = new Leilao('Fiat 147 0Km');
        $finalizado->finaliza();
    
        return [
            [
                [$naoFinalizado, $finalizado]
            ]
        ];
    }
}
?>