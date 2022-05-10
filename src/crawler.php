<?php

/**
* Ao utilizar o código deixar os créditos, livre para distribuição e utilização.
*/

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class WebCrawler {

    private $httpClient;
    private $xpath;
    private $data = [];

    /**
     * Inicia Guzzle
     */
    public function __construct() {
        $this->httpClient = new \GuzzleHttp\Client(array( 
            'curl'   => array(CURLOPT_SSL_VERIFYPEER => false),
            'verify' => false
          )
        );
    }

    /**
     * Obtem os dados de uma pagina especificada
     * @param $pageInt :: Numero de paginas
     */
    public function getPages($pageInt) {
        $totalPerPage = 0;
        for($i = 1; $i <= $pageInt; $i++) {
            $response = $this->httpClient->get("https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias?b_start:int={$totalPerPage}");
            $html = (string) $response->getBody();
            libxml_use_internal_errors(true);
            $page = new DOMDocument();
            $page->loadHTML($html);
            $this->xpath = new DOMXPath($page); 
            $this->saveExcel($this->getArrayOfInfos());
            $totalPerPage += 30;
        }  
    }

    /**
     * Realiza extração de dados
     */
    public function getArrayOfInfos() {
        
        $titles = $this->xpath->evaluate('//article//div//h2//a');
        $links  = $this->xpath->evaluate('//article//div//h2//a/@href');
        $day    = $this->xpath->evaluate('//article//span//span//i[@class="icon-day"]');
        $hour   = $this->xpath->evaluate('//article//span//span//i[@class="icon-hour"]');

        for($i = 0; $i < 30; $i++) {
            array_push($this->data, [
                'title' => $titles[$i]->textContent,
                'day'   => trim($day[$i]->parentNode->nodeValue),
                'hour'  => trim($hour[$i]->parentNode->nodeValue),
                'link'  => $links[$i]->textContent,
            ]);
        }
        
        return $this->data;
    }

    /**
     * Salva extração de dados em planilha excel
     * @param $data :: array de dados extraidos
     */
    public function saveExcel($data) { 
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Manchete');
        $sheet->setCellValue('C1', 'Hora');
        $sheet->setCellValue('B1', 'Dia');
        $sheet->setCellValue('D1', 'Link');
        $sheet->fromArray($data, null, 'A2');
        $writer = new Csv($spreadsheet);
        $writer->setUseBOM(true);
        $writer->setDelimiter(';');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $writer->save("planilha.csv");
    }
}

(new WebCrawler())->getPages(5);
