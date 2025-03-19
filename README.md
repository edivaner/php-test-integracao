# php-test-integracao

Iniciar o repositorio e executar os testes
1) Ter o PHP instalado, projeto é apenas para ter como exemplo na utilização do phpunit com integração

- Iniciar o composer
´
    composer install
´ 

- Sempre executar os testes pela paste do vendor
´
    ./vendor/bin/phpunit
´

- Se for utilizar um filtro, para executar apenas em arquivo de teste em específico
´
    ./vendor/bin/phpunit tests/Feature/Dao/LeilaoDaoTest.php
´
- Se for executar apenas um teste específico de um arquivo específico
´
    ./vendor/bin/phpunit tests/Feature/Dao/LeilaoDaoTest.php --filter=testBuscaLeiloesFinalizados
´