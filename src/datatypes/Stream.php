<?php
namespace pdflib\datatypes;

class Stream extends Indirect {
	private $data;
	
	public function __construct($number, $generation, $object = new Dictionary()){
		parent::__construct($number, $generation, $object);
	}
	
	public function append($data){
		$this->data.= $data;
	}
}