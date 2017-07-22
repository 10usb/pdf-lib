<?php
namespace pdflib\datatypes;

class Reference implements Object {
	private $number;
	private $generation;
	private $object;
	
	public function __construct($number, $generation, $object){
		$this->number		= $number;
		$this->generation	= $generation;
		$this->object		= $object;
	}
	
	public function output(){
		return $this->number.' '.$this->generation.' R';
	}
	
	public function getObject(){
		return $this->object;
	}
}