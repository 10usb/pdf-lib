<?php
namespace pdflib\xreferences;

class Entry {
	private $offset;
	
	private $generation;
	
	private $reference;
	
	
	public function __construct($offset, $generation, $reference){
		$this->offset		= $offset;
		$this->generation	= $generation;
		$this->reference	= $reference;
	}
}