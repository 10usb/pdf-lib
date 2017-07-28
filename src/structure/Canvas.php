<?php
namespace pdflib\structure;

use pdflib\datatypes\Text;

class Canvas {
	/**
	 * 
	 * @var number $width
	 * @var number $height
	 */
	private $width, $height;
	
	/**
	 * 
	 * @var \pdflib\datatypes\Stream
	 */
	private $stream;
	
	/**
	 * 
	 * @param number $width
	 * @param number $height
	 * @param \pdflib\datatypes\Stream $stream
	 */
	public function __construct($width, $height, $stream){
		$this->width	= $width;
		$this->height	= $height;
		$this->stream	= $stream;
		
		
		
		$this->stream->append(sprintf('%.2F w', 16)."\n");
		$this->stream->append(sprintf('%.3F %.3F %.3F RG', 0, 0, 0)."\n");
		$this->stream->append(sprintf('%.2F %.2F m %.2F %.2F l S', 0, 0, 100, 150)."\n");
	}
	
	/**
	 * 
	 * @param number $left
	 * @param number $top
	 * @param string $text
	 */
	public function text($left, $top, $text){
		$text = new Text($text);
		$this->stream->append(sprintf('BT %.2F %.2F Td %s Tj ET'."\n", $left, $this->height - $top, $text->output()));
	}
}