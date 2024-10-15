<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\DataExtractor;

class DataExtractorTest extends TestCase
{
    /**
     * @var DataExtractor
     */
    private $extractor;

    /**
     * Sample HTML to simulate a web page content.
     *
     * @var string
     */
    private $html;

    /**
     * Set up before each test.
     */
    protected function setUp(): void
    {
        $this->html = '
        <article>
            <div>
                <h2><a href="http://example.com/1">Title 1</a></h2>
            </div>
            <span>
                <span><i class="icon-day"></i>Monday</span>
                <span><i class="icon-hour"></i>10:00 AM</span>
            </span>
        </article>
        <article>
            <div>
                <h2><a href="http://example.com/2">Title 2</a></h2>
            </div>
            <span>
                <span><i class="icon-day"></i>Tuesday</span>
                <!-- Missing hour -->
            </span>
        </article>
        ';

        $this->extractor = new DataExtractor($this->html);
    }

    /**
     * Test to verify if data extraction works correctly.
     */
    public function testExtractDataWithMissingElements(): void {
        $result = $this->extractor->extractData();
        
        $this->assertCount(2, $result);
        $this->assertEquals('Title 1', $result[0]['title']);
        $this->assertEquals('Monday', $result[0]['day']);
        $this->assertEquals('10:00 AM', $result[0]['hour']);
        $this->assertEquals('http://example.com/1', $result[0]['link']);
    
        $this->assertEquals('Title 2', $result[1]['title']);
        $this->assertEquals('Tuesday', $result[1]['day']);
        $this->assertEquals('', $result[1]['hour']);
        $this->assertEquals('http://example.com/2', $result[1]['link']);
    }

    /**
     * Clean up after each test.
     */
    protected function tearDown(): void
    {
        $this->extractor = null;
    }
}
