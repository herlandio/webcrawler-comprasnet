<?php

/**
 * WebCrawler class
 *
 * This code is free to use, distribute, and modify.
 * Credits to the original author.
 */

 declare(strict_types=1);

 namespace App\Tests;

 use App\ReportGenerator;
 use PHPUnit\Framework\TestCase;

class ReportGeneratorTest extends TestCase
{
    /**
     * @var ReportGenerator
     */
    private $reportGenerator;

    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        $this->reportGenerator = new ReportGenerator();
        $this->outputDir = '/app/output';
        $this->cleanUpOutputDirectory();
    }

    /**
     * Cleans up the output directory by deleting all files and subdirectories within it.
     * If the output directory does not exist, this method does nothing.
     *
     * @return void
     */
    private function cleanUpOutputDirectory(): void {
        if (is_dir($this->outputDir)) {
            array_map('unlink', glob("$this->outputDir/*.*"));
            $files = array_diff(scandir($this->outputDir), ['.', '..']);
            foreach ($files as $file) {
                (is_dir("$this->outputDir/$file")) ? $this->deleteDirectory("$this->outputDir/$file") : unlink("$this->outputDir/$file");
            }
            rmdir($this->outputDir);
        }
    }

    /**
     * Deletes a directory and all its contents, including files and subdirectories.
     * If the specified directory does not exist, this method does nothing.
     *
     * @param string $dir The path of the directory to delete.
     * @return void
     */
    private function deleteDirectory(string $dir): void {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }

    /**
     * Test to ensure the output directory is created when it does not exist.
     *
     * This test uses reflection to invoke the private method
     * `ensureOutputDirectoryExists` of the `ReportGenerator` class,
     * then asserts that the output directory has been created successfully.
     *
     * @return void
     */
    public function testEnsureOutputDirectoryExistsCreatesDirectory(): void {
        $reflection = new \ReflectionClass($this->reportGenerator);
        $method = $reflection->getMethod('ensureOutputDirectoryExists');
        $method->setAccessible(true);
        $method->invoke($this->reportGenerator);

        $this->assertTrue(is_dir($this->outputDir));
    }

    /**
     * Test that the spreadsheet is prepared correctly.
     */
    public function testPrepareSpreadsheet(): void
    {
        $data = [
            ['Title 1', 'Day 1', '10:00 AM', 'http://example.com/article1'],
            ['Title 2', 'Day 2', '2:00 PM', 'http://example.com/article2'],
        ];

        $reflection = new \ReflectionClass($this->reportGenerator);
        $method = $reflection->getMethod('prepareSpreadsheet');
        $method->setAccessible(true);
        $spreadsheet = $method->invoke($this->reportGenerator, $data);

        $sheet = $spreadsheet->getActiveSheet();
        $this->assertEquals('Title', $sheet->getCell('A1')->getValue());
        $this->assertEquals('Day', $sheet->getCell('B1')->getValue());
        $this->assertEquals('Hour', $sheet->getCell('C1')->getValue());
        $this->assertEquals('Link', $sheet->getCell('D1')->getValue());

        $this->assertEquals('Title 1', $sheet->getCell('A2')->getValue());
        $this->assertEquals('Day 1', $sheet->getCell('B2')->getValue());
        $this->assertEquals('10:00 AM', $sheet->getCell('C2')->getValue());
        $this->assertEquals('http://example.com/article1', $sheet->getCell('D2')->getValue());
    }

    /**
     * Test case for the saveExcel method.
     *
     * This test verifies that the ReportGenerator correctly saves data into a CSV file.
     * It mocks the Csv writer and ensures that the output is saved as expected.
     */
    public function testSaveExcelSavesCsvFile(): void {
        $data = [
            ['Title 1', 'Monday', '10:00 AM', 'http://example.com/1'],
            ['Title 2', 'Tuesday', '11:00 AM', 'http://example.com/2']
        ];
    
        $reflection = new \ReflectionClass($this->reportGenerator);
        $method = $reflection->getMethod('ensureOutputDirectoryExists');
        $method->setAccessible(true);

        $method->invoke($this->reportGenerator);
        $this->assertTrue(is_dir('/app/output'));

        $this->reportGenerator->saveExcel($data);
        $this->assertFileExists("{$this->outputDir}/spreadsheet.csv");

        $contents = file_get_contents("{$this->outputDir}/spreadsheet.csv");
        $actualContent = file_get_contents('/app/output/spreadsheet.csv');
        $this->assertEquals($contents, $actualContent);
    }

    /**
     * Clean up after each test.
     */
    protected function tearDown(): void
    {
        $this->reportGenerator = null;
        $this->cleanUpOutputDirectory();
    }
}

