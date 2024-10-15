<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

declare(strict_types=1);

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * Class ReportGenerator
 *
 * This class handles the generation and saving of report data into a CSV file.
 */
class ReportGenerator {
    
    /**
     * @var string The directory where the CSV report will be saved.
     */
    private string $outputDir = DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR;
  
    /**
     * @var ?Csv The writer used for saving the CSV.
     */
    private ?Csv $writer;

    /**
     * ReportGenerator constructor.
     *
     * @param ?Csv|null $writer Optional writer for dependency injection (for testing).
     */
    public function __construct(?Csv $writer = null) {
        $this->writer = $writer; // Allow dependency injection
    }

    /**
     * Ensures the output directory exists. If not, it creates the directory.
     */
    private function ensureOutputDirectoryExists(): void {
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }

    /**
     * Prepares the spreadsheet by setting up the headers and filling it with data.
     *
     * @param array $data The data to be added to the spreadsheet.
     * @return Spreadsheet The prepared spreadsheet with the given data.
     */
    private function prepareSpreadsheet(array $data): Spreadsheet {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Title');
        $sheet->setCellValue('B1', 'Day');
        $sheet->setCellValue('C1', 'Hour');
        $sheet->setCellValue('D1', 'Link');
        $sheet->fromArray($data, null, 'A2');
        
        return $spreadsheet;
    }

    /**
     * Saves the spreadsheet data to a CSV file.
     *
     * @param array $data The data to be saved into the CSV.
     */
    public function saveExcel(array $data): void {
        $this->ensureOutputDirectoryExists();

        $spreadsheet = $this->prepareSpreadsheet($data);

        if ($this->writer === null) {
            $this->writer = new Csv($spreadsheet);
        } else {
            $this->writer->setSpreadsheet($spreadsheet);
        }

        $this->writer->setUseBOM(true);
        $this->writer->setDelimiter(';');
        $this->writer->setEnclosure('"');
        $this->writer->setLineEnding("\r\n");
        $this->writer->setSheetIndex(0);

        $filePath = "{$this->outputDir}spreadsheet.csv";
        
        $this->writer->save($filePath);
        
        file_put_contents($filePath, "\r\n", FILE_APPEND);
    }
}
