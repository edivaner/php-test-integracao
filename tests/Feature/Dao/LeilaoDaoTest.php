<?php
namespace Alura\Leilao\Tests\Feature\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
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
    
    public function testInsercaoEBuscaDevemFuncionar(){
        // arrange
        $leilao = new Leilao('Fiat 147 0Km');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilaoDao->salva($leilao);
        // assert
        $leiloes = $leilaoDao->recuperarNaoFinalizados();
        // assert
        self::assertCount(1, $leiloes);
        self::assertEquals('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
    }
}
?>