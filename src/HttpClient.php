<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

 declare(strict_types=1);

 namespace App;
 
 use GuzzleHttp\Client;
 
/**
 * Class HttpClient
 *
 * This class provides an HTTP client for making requests using Guzzle.
 */
class HttpClient {

    /**
     * @var Client The Guzzle HTTP client instance.
     */
    protected $client;

    /**
     * HttpClient constructor.
     *
     * Initializes the Guzzle HTTP client with custom configuration.
     * SSL verification is disabled for all requests.
     */
    public function __construct() {
        $this->client = new Client([
            'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            'verify' => false
        ]);
    }

    /**
     * Sends a GET request to the specified URL and returns the response body.
     *
     * @param string $url The URL to which the GET request is sent.
     * @return string The response body from the GET request.
     */
    public function get(string $url): string {
        $response = $this->client->get($url);
        return (string) $response->getBody();
    }
}
