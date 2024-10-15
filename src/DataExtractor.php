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
 * Class DataExtractor
 *
 * This class is responsible for extracting data from a given HTML document using DOMXPath.
 */
class DataExtractor {
    
    /**
     * @var \DOMXPath The DOMXPath object used to query the HTML document.
     */
    private \DOMXPath $xpath;

    /**
     * @var array The array where the extracted data will be stored.
     */
    private array $data = [];

    /**
     * DataExtractor constructor.
     *
     * Initializes a DOMXPath object from the provided HTML string.
     *
     * @param string $html The HTML content to be processed.
     */
    public function __construct(string $html) {
        libxml_use_internal_errors(true);
        $page = new \DOMDocument();
        $page->loadHTML($html);
        $this->xpath = new \DOMXPath($page);
    }

    /**
     * Extracts data from the HTML document.
     *
     * The method extracts titles, links, days, and hours from the article elements in the HTML.
     *
     * @return array The extracted data, each entry contains 'title', 'day', 'hour', and 'link'.
     */
    public function extractData(): array {
        $titles = $this->xpath->evaluate('//article//div//h2//a');
        $links  = $this->xpath->evaluate('//article//div//h2//a/@href');
        $days   = $this->xpath->evaluate('//article//span//span//i[@class="icon-day"]');
        $hours  = $this->xpath->evaluate('//article//span//span//i[@class="icon-hour"]');
    
        $count = max($titles->length, $links->length, $days->length, $hours->length);
    
        for ($i = 0; $i < $count; $i++) {
            $this->data[] = [
                'title' => ($titles->length > $i) ? $titles[$i]->textContent : '',
                'day' => ($days->length > $i) ? trim($days[$i]->parentNode->nodeValue) : '',
                'hour' => ($hours->length > $i) ? trim($hours[$i]->parentNode->nodeValue) : '',
                'link' => ($links->length > $i) ? $links[$i]->textContent : '',
            ];
        }
    
        return $this->data;
    }
}
