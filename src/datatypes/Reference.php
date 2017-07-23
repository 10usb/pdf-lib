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
	
	public function getNumber(){
		return $this->number;
	}
	
	public function getGeneration(){
		return $this->generation;
	}
	
	public function getObject(){
		return $this->object;
	}
	
	public function getBody(){
		return 'hoi';
	}
}