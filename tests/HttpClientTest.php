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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use App\HttpClient;

class HttpClientTest extends TestCase
{
    /**
     * @var ?HttpClient
     */
    private ?HttpClient $httpClient;

    /**
     * @var ?Client
     */
    private ?Client $mockClient;

    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(Client::class);
        $this->httpClient = new class($this->mockClient) extends HttpClient {
            public function __construct(Client $client) {
                $this->client = $client;
            }
        };
    }

    /**
     * Test that the GET request method returns the expected response.
     */
    public function testGetRequestReturnsResponseBody(): void
    {
        $url = 'http://example.com';
        $expectedBody = 'Sample response';
        $mockResponse = new Response(200, [], $expectedBody);
        $this->mockClient
            ->method('get')
            ->with($url)
            ->willReturn($mockResponse);
        $result = $this->httpClient->get($url);
        $this->assertEquals($expectedBody, $result);
    }

    /**
     * Clean up after each test.
     */
    protected function tearDown(): void
    {
        $this->httpClient = null;
        $this->mockClient = null;
    }
}
