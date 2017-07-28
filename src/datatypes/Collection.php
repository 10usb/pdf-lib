<?php
namespace pdflib\datatypes;

class Collection implements Object {
	private $values;
	
	public function __construct($values = []){
		$this->values = [];
		foreach($values as $value) $this->push($value);
	}
	
	public function output(){
		$outputs = [];
		foreach($this->values as $value){
			$outputs[] = $value->output();
		}
		return '['.implode(' ', $outputs).']';
	}
	
	public function get($index){
		if(!isset($this->values[$index])) throw new \Exception('Index out of bound');
		return $this->values[$index];
	}
	
	public function push($value){
		if($value instanceof Object){
			$this->values[] = $value;
		}elseif(is_array($value)){
			$this->values[] = new self($value);
		}else{
			throw new \Exception('Not an object type');
		}
		return $this;
	}
}