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
        )->setChromeBinary('chromium-browser');

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
        $result = $this->converter->setUrl('http://127.0.0.1:8000/user/122/sa')->toPdf($pdfFile);
        $this->assertTrue($result);
        $this->assertFileExists($pdfFile);
    }

    public function testToPng()
    {
        $pngFile = sys_get_temp_dir()."/test_png.png";
        $this->deleteFile($pngFile);
        $result = $this->converter->setUrl('http://127.0.0.1:8000/user/122/sa')->toPng($pngFile);
        $this->assertTrue($result);
        $this->assertFileExists($pngFile);
    }

    private function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
