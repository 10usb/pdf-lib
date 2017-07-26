<?php
namespace pdflib\datatypes;

class Reference implements Object, Referenceable {
	private $number;
	private $generation;
	
	public function __construct($number, $generation){
		$this->number		= $number;
		$this->generation	= $generation;
	}
	
	public function output(){
		return $this->number.' '.$this->generation.' R';
	}
	
	public function getNumber(){
		return $this->number;
	}
	
	public function getGeneration(){
		return $this->generation;
	}
}