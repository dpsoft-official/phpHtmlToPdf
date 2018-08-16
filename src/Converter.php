<?php

namespace Dpsoft\HtmlToPdf;

use Symfony\Component\Process\Process;

class Converter
{
    CONST CHROME_VERSION_PARAM = "--version";
    CONST GOOGLE_CHROME = "google-chrome";
    CONST CHROMIUM_BROWSER = "chromium-browser";
    /**
     * chrome browser binary path
     *
     * @var string
     */
    private $chromeBinary;
    /**
     * @var array
     */
    private $parameters;

    /**
     * Converter constructor.
     *
     * @param string|null $chromeBinary custom chrome binary
     * @param array       $parameters
     */
    public function __construct(string $chromeBinary = null, array $parameters = [])
    {

        $this->chromeBinary = $chromeBinary;
        $this->parameters = array_merge(['--headless', '--disable-gpu'], $parameters);
    }

    /**
     * @return string
     */
    public function getChromeBinary(): string
    {
        return $this->chromeBinary;
    }

    /**
     * set custom chrome binary
     *
     * @param string $chromeBinary
     *
     * @return Converter
     */
    public function setChromeBinary(string $chromeBinary)
    {
        $this->chromeBinary = $chromeBinary;

        return $this;
    }

    /**
     * set url or html file for export
     *
     * @param string $url
     *
     * @return Converter
     */
    public function setUrl(string $url)
    {
        $this->setParameters(["$url"]);

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * set chrome cli parameters
     *
     * @param array $parameters
     *
     * @return Converter
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * export url to pdf
     *
     * @param $pdfName
     *
     * @return bool
     * @throws \Exception
     */
    public function toPdf($pdfName)
    {
        $this->setParameters(["--print-to-pdf='$pdfName'"]);
        $stat = $this->runCommand();
        if (!$stat->isSuccessful()) {
            throw new \Exception($stat->getOutput(), 2);
        }

        return true;
    }

    /**
     * export url to png
     *
     * @param $pngName
     *
     * @return bool
     * @throws \Exception
     */
    public function toPng($pngName)
    {
        $this->setParameters(["--screenshot='$pngName'"]);
        $stat = $this->runCommand();
        if (!$stat->isSuccessful()) {
            throw new \Exception($stat->getOutput(), 2);
        }

        return true;
    }

    /**
     * @return Process
     * @throws \Exception
     */
    private function runCommand()
    {
        $command = new Process($this->checkChromeBinary()." ".implode(' ', $this->parameters));
        $command->run();

        return $command;
    }

    /**
     * validate and get chrome binary
     *
     * @return string
     * @throws \Exception
     */
    public function checkChromeBinary()
    {
        if ($this->chromeBinary) {
            $this->getVersion($this->chromeBinary);

            return $this->chromeBinary;
        }
        try {
            $this->getVersion(self::GOOGLE_CHROME);

            return self::GOOGLE_CHROME;
        } catch (\Exception $exception) {
            $this->getVersion(self::CHROMIUM_BROWSER);

            return self::CHROMIUM_BROWSER;
        }
    }

    /**
     * get chrome version
     *
     * @param $binary
     *
     * @return mixed
     * @throws \Exception
     */
    private function getVersion($binary)
    {
        $command = new Process([$binary, self::CHROME_VERSION_PARAM]);
        $command->run();
        if (!$command->isSuccessful()) {
            throw new \Exception('Set invalid binary: '.$binary, 1);
        }

        return $binary;
    }

    /**
     * hide scrollbar on export
     *
     * @return $this
     */
    public function setHideScrollbar()
    {
        $this->setParameters(['--hide-scrollbars']);

        return $this;
    }

    /**
     * send chrome windows size on image export
     *
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function setWindowSize(int $width = 1280, int $height = 1696)
    {
        $this->setParameters(["--window-size=$width,$height"]);

        return $this;
    }

    /**
     * set custom user agent
     *
     * @param $agent
     *
     * @return $this
     */
    public function setUserAgent($agent)
    {
        $this->setParameters(["--user-agent='$agent'"]);

        return $this;
    }
}