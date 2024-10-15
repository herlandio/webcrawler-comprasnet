<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

declare(strict_types=1);

namespace App;

/**
 * Class WebCrawler
 *
 * Responsible for fetching web pages and saving extracted data to a CSV file.
 */
class WebCrawler {

    /**
     * @var HttpClient
     */
    private HttpClient $httpClient;

    /**
     * @var ReportGenerator
     */
    private ReportGenerator $reportGenerator;

    /**
     * Constructor of the WebCrawler class.
     *
     * Initializes the HTTP client and the report generator.
     */
    public function __construct() {
        $this->httpClient = new HttpClient();
        $this->reportGenerator = new ReportGenerator();
    }

    /**
     * Retrieves data from a specified number of pages.
     *
     * @param int $pageInt Number of pages to fetch.
     * @return array Extracted data from the pages.
     */
    public function getPages(int $pageInt): array {
        $data = [];
        $totalPerPage = 0;

        for ($i = 1; $i <= $pageInt; $i++) {
            $html = $this->httpClient->get("https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias?b_start:int={$totalPerPage}");
            $extractor = new DataExtractor($html);
            $data = array_merge($data, $extractor->extractData());
            $totalPerPage += 30;
        }

        return $data;
    }

    /**
     * Saves the extracted data to a CSV file.
     *
     * @param array $data Data to be saved.
     */
    public function saveData(array $data): void {
        $this->reportGenerator->saveExcel($data);
    }
}

