# Web Crawler para extração de dados da pagina [ComprasNet](https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias)

- Clone o projeto

  ```
  git clone https://github.com/herlandio/webcrawler-comprasnet
  ```
  
- Acesse a pasta webcrawler-comprasnet/ e execute os comandos um por vez
- Cria a imagem docker
  ```
  $ docker build -t webcrawler:v1 .
  ```
  Salva os dados em um arquivo excel na pasta output. Certifique-se de que essa pasta exista; caso a mesma não seja criada automaticamente.
  ```
  $ docker run --rm -v "$(pwd)/output:/app/output" webcrawler:v1
  ```
  Executa os testes
  ```
  $ docker run --rm -it webcrawler:v1 ./vendor/bin/phpunit tests
  ```
