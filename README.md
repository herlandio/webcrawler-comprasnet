# Web Crawler para extração de dados da pagina [ComprasNet](https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias)

- Clone o projeto

  ```
  git clone https://github.com/herlandio/webCrawler
  ```
  
- Acesse a pasta Web-Crawler-ComprasNet/ e Instale as dependências utilizando o comando abaixo

  ```
  composer install
  ```
  
- No arquivo src/crawler.php basta definir de quantas paginas serão extraidas as informações

  ```
  (new WebCrawler())->getPages(5);
  ```
  
- Para executar o script acesse a pasta src/ e execute em linha de comando CMD ou Terminal

  ```
  php crawler.php
  ```
  
- Após a execução do script ira gerar uma planilha excel com todas as informações exportadas 

  - A planilha estará na pasta src

- OBS: a extenção extension=gd2 ou extension=gd deve estar habilitada no php.ini.
