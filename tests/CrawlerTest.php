<?php

declare(strict_types=1);

require './src/crawler.php';

use PHPUnit\Framework\TestCase;

final class CrawlerTest extends TestCase {

    public function testArrayOfInfos(): void {
        $instance = new WebCrawler();
        $instance->getPages(5);
        $this->assertIsArray($instance->getArrayOfInfos());
    }

    public function testCount(): void {
        $instance = new WebCrawler();
        $this->assertEquals(150, count($instance->getPages(5)));
    }

}
