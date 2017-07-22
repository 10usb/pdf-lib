<?php
namespace pdflib\xreferences;

class Section {
	private $number;
	
	private $entries;
	
	public function __construct($number){
		$this->number = $number;
		$this->entries = [];
	}
	
	public function add($offset, $generation, $used, $reference = null){
		$entry = new Entry($offset, $generation, $used, $reference);
		$this->entries[] = $entry;
		return $entry;
	}
}