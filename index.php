<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

declare(strict_types=1);

require_once './vendor/autoload.php';

use App\WebCrawler;

try {
    $webCrawler = new WebCrawler();
    $data = $webCrawler->getPages(5);
    $webCrawler->saveData($data);
    echo "Dados extraídos e salvos com sucesso!";
} catch (\Exception $e) {
    echo "Ocorreu um erro: " . $e->getMessage();
}
