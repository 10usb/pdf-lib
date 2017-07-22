<?php
namespace pdflib\datatypes;

class Dictionary implements Object {
	private $entries;
	
	public function __construct(){
		$this->entries = [];
	}
	
	public function output(){
		$outputs = [];
		foreach($this->entries as $value){
			$outputs[] = $value->key->output().' '.$value->value->output();
		}
		return '<<'.implode(' ', $outputs).'>>';
	}
	
	public function set($key, $value){
		if(!$value instanceof Object){
			throw new \Exception('Not an object type');
		}
		
		foreach($this->entries as $entry){
			if($entry->key == $key){
				$entry->value = $value;
				return $this;
			}
		}
		
		$entry = new \stdClass();
		$entry->key		= new Name($key);
		$entry->value	= $value;
		$this->entries[] = $entry;
		return $this;
	}
}