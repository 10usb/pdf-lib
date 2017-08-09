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
	}
	
	/**
	 * 
	 * @param number $r
	 * @param number $g
	 * @param number $b
	 */
	public function setStrokeColor($r, $g, $b){
		$this->stream->append(sprintf("%.3F %.3F %.3F RG\n", $r / 255, $g / 255, $b / 255));
		return $this;
	}
	
	/**
	 * 
	 * @param number $r
	 * @param number $g
	 * @param number $b
	 */
	public function setFillColor($r, $g, $b){
		$this->stream->append(sprintf("%.3F %.3F %.3F rg\n",$r / 255, $g / 255, $b / 255));
		return $this;
	}
	
	/**
	 * 
	 * @param number $width
	 */
	public function setLineWidth($width){
		$this->stream->append(sprintf("%.2F w\n", $width));
		return $this;
	}
	
	/**
	 * 
	 * @param number $x1
	 * @param number $y1
	 * @param number $x2
	 * @param number $y2
	 */
	public function line($x1, $y1, $x2, $y2){
		$this->stream->append(sprintf("%.2F %.2F m %.2F %.2F l S\n", $x1, $this->height - $y1, $x2, $this->height - $y2));
		return $this;
	}
	
	/**
	 * 
	 * @param number $x
	 * @param number $y
	 * @param number $w
	 * @param number $h
	 * @param boolean $filled
	 * @param boolean $border
	 */
	public function rectangle($x, $y, $w, $h, $filled=true, $border = false){
		if($filled && $border){
			$this->stream->append(sprintf("%.2F %.2F %.2F %.2F re B\n", $x, $this->height - $y, $w, -$h));
		}elseif($filled){
			$this->stream->append(sprintf("%.2F %.2F %.2F %.2F re f\n", $x, $this->height - $y, $w, -$h));
		}elseif($border){
			$this->stream->append(sprintf("%.2F %.2F %.2F %.2F re S\n", $x, $this->height - $y, $w, -$h));
		}
	}
	
	/**
	 * 
	 * @param number $x
	 * @param number $y
	 * @param number $w
	 * @param number $h
	 * @param \pdflib\structure\Image $image
	 */
	public function image($x, $y, $w, $h, $image){
		$this->stream->append(sprintf("q %.2F 0 0 %.2F %.2F %.2F cm %s Do Q\n", $w, $h, $x, $this->height - ($y + $h), $image->getLocalName()->output()));
	}
	
	/**
	 * 
	 * @param \pdflib\structure\Font $font
	 * @return \pdflib\structure\Canvas
	 */
	public function setFont($font){
		$this->stream->append(sprintf("BT %s %.2F Tf ET\n", $font->getLocalName()->output(), $font->getSize()));
		return $this;
	}
	
	/**
	 * 
	 * @param number $left
	 * @param number $top
	 * @param string $text
	 */
	public function text($left, $top, $text){
		$text = new Text($text);
		$this->stream->append(sprintf("BT %.2F %.2F Td %s Tj ET\n", $left, $this->height - $top, $text->output()));
	}
}