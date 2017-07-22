<?php
namespace pdflib\xreferences;

class Section {
	private $number;
	
	private $entries;
	
	public function __construct($number){
		$this->number = $number;
		$this->entries = [];
	}
	
	public function add($offset, $generation, $reference){
		$entry = new Entry($offset, $generation, $reference);
		$this->entries[] = $entry;
		return $entry;
	}
}