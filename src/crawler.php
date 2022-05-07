<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $pageInt = ($pageInt === 1) ? 0 : $pageInt;
        for($i = 1; $i <= (($pageInt === 0) ? 1 : $pageInt); $i++) {
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
        
        $titles = $this->xpath->evaluate('//article[@class="tileItem visualIEFloatFix tile-collective-nitf-content"]//div[@class="tileContent"]//h2[@class="tileHeadline"]/a');
        $links  = $this->xpath->evaluate('//article[@class="tileItem visualIEFloatFix tile-collective-nitf-content"]//div[@class="tileContent"]//h2[@class="tileHeadline"]//a/@href');
        $day    = $this->xpath->evaluate('//article[@class="tileItem visualIEFloatFix tile-collective-nitf-content"]//span[@class="documentByLine"]//span[@class="summary-view-icon"]//i[@class="icon-day"]');
        $hour   = $this->xpath->evaluate('//article[@class="tileItem visualIEFloatFix tile-collective-nitf-content"]//span[@class="documentByLine"]//span[@class="summary-view-icon"]//i[@class="icon-hour"]');

        for($i = 0; $i < $titles->length; $i++) {
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
        $writer = new Xlsx($spreadsheet);
        $writer->save('planilha.xlsx');
    }
}

(new WebCrawler())->getPages(5);