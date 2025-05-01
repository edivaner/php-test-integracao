<?php
namespace Alura\Leilao\Tests\Feature\Web;

use PHPUnit\Framework\TestCase;

class RestTest extends TestCase {
    private $response;
    private $leiloes;
    private $headerResponse;
    
    protected function setUp(): void
    {
        //Pega o response da API (da maneira que preferir)
        $this->response = file_get_contents('http://localhost:8080/rest.php');
        $this->leiloes = json_decode($this->response, true);
        $this->headerResponse = $http_response_header;
    }

    public function testApiRestDeveRetornarArrayDeLeiloes() {
        self::assertStringContainsString("200 OK", $this->headerResponse[0]);
        self::assertIsArray(json_decode($this->response, true));
    }

    public function testNenhumLeilaoDeveEstaFinalizado() {
        
        foreach ($this->leiloes as $leilao) {
            self::assertFalse($leilao['estaFinalizado']);
        }
    }

    public function testLeiloesTemDescricaoEStatus(){
        foreach ($this->leiloes as $leilao) {
            self::assertArrayHasKey('descricao', $leilao);
            self::assertArrayHasKey('estaFinalizado', $leilao);
        }
    }
}
?>