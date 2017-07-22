<?php
namespace pdflib\datatypes;

class Stream extends Reference {
	private $data;
	
	public function __construct($number, $generation){
		parent::__construct($number, $generation, new Dictionary());
	}
	
	public function append($data){
		$this->data.= $data;
	}
}