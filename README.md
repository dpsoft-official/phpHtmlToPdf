# Url to Pdf/image export in Php
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

Convert any url or html to pdf/image using google-chrome or chromium-browser 

## Installation
``` bash
$ composer require dpsoft/php-html-to-pdf
```
## Requirement
This package uses google-chrome/chromium-browser.
for example install chromium-browser on linux ubuntu:
```bash
~ sudo apt install chromium-browser
```
To verify requirement, run following test from package folder:
```bash
~ composer test
```
## Implementation

Use url or html file to export:

```php
require "vendor/autoload.php";

use Dpsoft\HtmlToPdf\Converter;

$converter = new Converter();

// export to pdf
$convertor->setUrl('http://google.com')->toPdf('pdf/file/name.pdf');

// or export to png
$convertor->setUrl('http://google.com')->toPng('png/file/name.png');

// you can set window size for png export
$convertor->setUrl('http://google.com')->setWindow(1280,960)->toPng('png/file/name.png');
```
## Test
Run test:
```bash
~ composer test
```
