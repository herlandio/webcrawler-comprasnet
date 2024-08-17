<?php

declare(strict_types=1);

require './src/crawler.php';

use PHPUnit\Framework\TestCase;

final class CrawlerTest extends TestCase {

    private WebCrawler $instance;
    
    protected function setUp() {
        $this->instance = new WebCrawler();
    }
    
    public function testArrayOfInfos(): void {
        $this->instance->getPages(5);
        $this->assertIsArray($this->instance->getArrayOfInfos());
    }

    public function testCount(): void {
        $this->assertEquals(150, count($this->instance->getPages(5)));
    }

}
