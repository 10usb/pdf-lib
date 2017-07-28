<?php
namespace pdflib\datatypes;

class Stream extends Indirect {
	private $data;
	
	public function __construct($number, $generation, $object = null){
		parent::__construct($number, $generation, $object ? $object : new Dictionary());
	}
	
	public function append($data){
		$this->data.= $data;
		$this->getObject()->set('Length', new Number(strlen($this->data)));
	}
	
	public function getBody(){
		$lines = parent::getBody();
		$lines[] = 'stream';
		$lines[] = $this->data;
		$lines[] = 'endstream';
		return $lines;
	}
}