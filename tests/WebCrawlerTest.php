<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

declare(strict_types=1);

namespace App\Tests;

use App\WebCrawler;
use PHPUnit\Framework\TestCase;
use App\HttpClient;
use App\DataExtractor;
use App\ReportGenerator;

class WebCrawlerTest extends TestCase {

    /**
     * @var WebCrawler
     */
    private $webCrawler;

    /**
     * @inheritDoc
     * @return void
     */
    protected function setUp(): void {
        $httpClientMock         = $this->createMock(HttpClient::class);
        $dataExtractorMock      = $this->createMock(DataExtractor::class);
        $reportGeneratorMock    = $this->createMock(ReportGenerator::class);

        $httpClientMock
            ->method('get')
            ->willReturn('<html>...</html>');
        $dataExtractorMock
            ->method('extractData')
            ->willReturn([
                [
                    'title' => 'Sample Title',
                    'day' => '1',
                    'hour' => '12:00',
                    'link' => 'http://example.com'
                ]
            ]);
        
        $this->webCrawler = new WebCrawler($httpClientMock, $dataExtractorMock, $reportGeneratorMock);
    }

    /**
     * Test numbers results per page
     *
     * @return void
     */
    public function testNumbersResultsPerPage(): void {
        $data = $this->webCrawler->getPages(1);
        $this->assertIsArray($data);
        $this->assertCount(30, $data);
    }

    /**
     * Test count results
     *
     * @return void
     */
    public function testCount(): void {
        $data = $this->webCrawler->getPages(5);
        $this->assertCount(150, $data);
    }

    /**
     * @inheritDoc
     * @return void
     */
    protected function tearDown(): void {
        $this->webCrawler = null;
    }
}
