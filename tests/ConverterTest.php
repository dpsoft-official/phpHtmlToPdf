<?php

namespace Tests;

use Dpsoft\HtmlToPdf\Converter;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;

class ConverterTest extends TestCase
{
    /**
     * @var Converter
     */
    private $converter;

    public function run(TestResult $result = null)
    {
        $this->converter = (new Converter())->setHideScrollbar()->setWindowSize()->setUserAgent(
            'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
        );

        return parent::run($result);
    }

    public function testCheckChromeBinary()
    {
        $binary = $this->converter->checkChromeBinary();
        $this->assertNotEmpty($binary, 'chrome or chromium binary not found on system.');
    }

    public function testToPdf()
    {
        $pdfFile = sys_get_temp_dir()."/test_pdf.pdf";
        $this->deleteFile($pdfFile);
        $result = $this->converter->setUrl('http://google.com')->toPdf($pdfFile);
        $this->assertTrue($result);
        $this->assertFileExists($pdfFile);
    }

    public function testToPng()
    {
        $pngFile = sys_get_temp_dir()."/test_png.png";
        $this->deleteFile($pngFile);
        $result = $this->converter->setUrl('http://google.com')->toPng($pngFile);
        $this->assertTrue($result);
        $this->assertFileExists($pngFile);
    }

    public function testToPdfWithUsePhpExec()
    {
        $pdfFile = sys_get_temp_dir()."/test_pdf_use_php_exec.pdf";
        $this->deleteFile($pdfFile);
        $result = $this->converter->setUsePhpExec(true)->setUrl('http://google.com')->toPdf($pdfFile);
        $this->assertTrue($result);
        $this->assertFileExists($pdfFile);
    }

    private function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
