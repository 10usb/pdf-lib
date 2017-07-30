<?php
use pdflib\File;

header('Content-Type: text/plain');

require_once '../autoloader.php';

$start = microtime(true);

$file = new File('test.pdf');
$file->getInformation()
	->setTitle('My PDF Library')
	->setSubject('How to create a pdf library')
	->setAuthor('10usb');

$catalog = $file->getCatalog()->setSize(595.276, 841.890);

// Add a page but overrule the size
$page = $catalog->addPage()->setSize(450, 450);

// Get a canvas object from the page and start redering on it
$canvas = $page->getCanvas();

$canvas->image(30, 30, 48, 48, $page->getImage('logo.png'));


$canvas->setFont($font = $page->getFont('Times-BoldItalic', 26));

$canvas->setFillColor(192, 192, 192);
$canvas->text(90.8, 30.8+ $font->getSize(), "PDF Library");

$canvas->setFillColor(66, 66, 66);
$canvas->text(90, 30 + $font->getSize(), "PDF Library");

$canvas->setFillColor(92, 92, 92);
$canvas->setFont($font = $page->getFont('Helvetica', 11));
$canvas->text(90, 64 + $font->getSize(), "pdf-lib a simple layer around a PDF file made by 10usb");


$canvas->setStrokeColor(0, 0, 0);
$canvas->setLineWidth(1.5);
$canvas->line(30, 86, $page->getWidth() - 30, 86);


$lines = [];
$lines[] = 'A PHP PDF library that is not created to easily add rich content (HTML etc) to a';
$lines[] = 'PDF file. But rather allowing any valid PDF content to be added to the file without';
$lines[] = 'the excess of functionality that "tries" to emulate HTML like behavior and';
$lines[] = 'limit/complicate simple tasks. This library takes the concept that every page is no';
$lines[] = 'more then just a canvas area that can takes 2D graphics rendering commands.';
$lines[] = 'Any other functionality there might be is considers meta data.';

$canvas->setFillColor(66, 66, 66);
foreach($lines as $index=>$line){
	$canvas->text(30, 112 + $index * $font->getSize() * 1.5, $line);
}


$page = $catalog->addPage();
$canvas = $page->getCanvas();

$canvas->setFont($font = $page->getFont('Times-BoldItalic', 22));

$canvas->setFillColor(192, 192, 192);
$canvas->text(30.8, 30.8+ $font->getSize(), "Images");

$canvas->setFillColor(66, 66, 66);
$canvas->text(30, 30 + $font->getSize(), "Images");


$canvas->image(30, 80, 535, 300, $page->getImage('DSC_0489.JPG'));


$canvas->setFillColor(192, 192, 192);
$canvas->text(30.8, 400.8 + $font->getSize(), "Rectangles");

$canvas->setFillColor(66, 66, 66);
$canvas->text(30, 400 + $font->getSize(), "Rectangles");


$canvas->setStrokeColor(66, 66, 66);
$canvas->setFillColor(192, 192, 192);

$canvas->rectangle(30, 450, 535, 200);

$canvas->setFillColor(66, 66, 192);
$canvas->rectangle(30 + (535 - 250) / 2, 550, 250, 200, true, true);
$canvas->rectangle(30 + (535 - 450) / 2, 475, 450, 325, false, true);

$file->flush();

if(isset($_GET['stats'])){
	printf("generated in: %ss\n", round(microtime(true) - $start, 4));
	printf("memory use: %sMB\n", round(memory_get_peak_usage() / 1024 / 1024, 2));
	exit;
}


header('Content-Type: application/pdf');
header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
header('Pragma: public');
header('Expires: '.date('D, d M Y H:i:s z'));
header('Content-Disposition: inline; filename="basic.pdf');
echo $file->getContents();