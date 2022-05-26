# Web Crawler para extração de dados da pagina [ComprasNet](https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias)

- Clone o projeto

  ```
  git clone https://github.com/herlandio/webcrawler-comprasnet
  ```
  
- Acesse a pasta webcrawler-comprasnet/ e execute os comandos um por vez

  ```
  Cria a imagem docker
  $ docker build -t webcrawler:v1 .

  Inicia a imagem
  $ docker run -it -d webcrawler:v1

  Execute este comando para ver o CONTAINER ID da imagem
  $ docker ps

  OBS: substitua o CONTAINER ID pelo encontrado atraves do comando acima.

  Executa script webcrawler, dentro de alguns segundos ira gerar a planilha.csv
  $ docker exec -it CONTAINER_ID php src/crawler.php

  Executa os testes
  $ docker exec -it CONTAINER_ID ./vendor/bin/phpunit tests

  Acessa o bash do container
  $ docker exec -it CONTAINER_ID bash

  Lista o conteudo, a planilha gerada estará aqui
  $ ls

  Sai do bash do container
  $ exit
  ```
  
- No arquivo webcrawler-comprasnet/src/crawler.php basta definir de quantas paginas serão extraidas as informações

  ```
  $WebCrawler = new WebCrawler();
  $WebCrawler->saveExcel($WebCrawler->getPages(5));
  ```