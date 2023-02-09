# BrasilTecParChallenge
Desafio para vaga de programador em que tivemos como requisito os seguintes pontos

**Rota para geração de hash**

A criação de uma rota que deve receber uma string como parâmetro.

Gerar uma chave aleatória e após concatenar a chave com a string, gerar o hash md5 dela. 

Criar um controle de 10 requisições por minuto, e gerar um "Too Many Requests" quando esse limite é ultrapassado

**Comando para consulta da rota**

Criar comando para consultar a rota criada anteriormente, permitindo que seja possível enviar o número de requisições
e que para cada requisição feita dentro de um lote a entrada de texto receba a hash gerada na requisição anterior (com exceção da primeira requisição)

**Rota de retorno dos resultados**

Criar rota para retorno dos resultados gerados pelo requisito anterior.

A rota deve retornar os resultados de forma paginada.

A rota deve ter um filtro por "Número de tentativas" mostrando os resultados que tiveram menos de x tentativas.

A rota deve retornar apenas as colunas "batch", "número de bloco", "string de entrada" e "chave encontrada".

## Instalação e Configuração

#### Pré-requisitos
- Docker instalado
- Makefile ou build-essentials instalado (opcional)
****
Para configurarmos a aplicação executamos os seguintes comandos:
```
docker compose build --no-cache
docker compose up -d
```

ou usando o Makefile execute
```
make build
```

## Executando a aplicação
Vamos tratar em como executar cada requisito da aplicação

Obs: Temos uma documentação no Postman para auxiliar na execução das rotas, para acessar clique aqui

****
**Rota para geração de hash**

A rota para gerar o hash pode ser acessado usando o Postman (Vê link acima) ou pelo curl usando o comando:

```
curl --location --request POST 'http://localhost:8081/hash/<string>'
```
Onde o < string > é a string de entrada que vai ser usada para gerar o hash

**Comando para consulta da rota**

O comando para realizar essa consulta em lote segue o seguinte padrão
```avato:test <string> --requests=<numero de requests>```

Onde o < string > é a string de entrada que vai ser usada inicialmente no lote e o < numero de requests > é 
a quantidade de requisições que serão feitas nesse comando.

Para executar o comando digite
```
docker compose exec php-fpm sh
php bin/console avato:test <string> --requests=<numero de requests>
```

Ou com o Makefile
```
make shell
php bin/console avato:test <string> --requests=<numero de requests>
```

Para consultar o banco e vê os resultados acesse aqui

**Rota de retorno dos resultados**
A rota para gerar o hash pode ser acessado pelo curl usando o comando:
```
curl --location --request GET 'http://localhost:8000/hash'
```

Temos os seguintes parâmetros opcionais

Parâmetro   | Descrição                                  | Valor padrão         | Exemplo 
--------- |--------------------------------------------|----------------------| ---------
Page | Determina a página da consulta             | 1                    | ```/hash?page=2```  
Per Page | Determina a quantidade de itens por página | 10                   | ```/hash?per_page=5```  
Amount Tries | Filtro pelo campo "numero de tentativas"   | Não tem valor padrão | ```/hash?amount_tries=100000```  


## Ferramentas auxiliares
Aqui temos alguns comandos que podem ser utéis 
***
**Realizando migrações**

Caso você deseje realizar migrações, use o comando
```
docker compose exec php-fpm sh
php bin/console doctrine:migrations:migrate
```

Ou com o Makefile
```
make migrate
```

**Banco de dados**

Para consultar o banco de dados, use o comando
```
docker compose exec database sh
mysql -u app -p
```

Ou com o Makefile
```
make db
```
Após isso digite a senha (presente no .env)

Abaixo temos uma dica de sql para realizar a consulta.

```
use brasiltecparchallenge;
select * from hash;
```
Obs: A visualização no terminal pode acabar resumindo um pouco os dados, para grande quantidade de dados usar um where ou fazer a consulta por uma  IDE pode ser melhor

**Testando a aplicação**

Para testar a aplicação use o comando
```
docker compose exec php-fpm sh
php bin/console doctrine:migrations:migrate --env=test
php bin/phpunit tests
```

Ou com o Makefile
```
make test
```
