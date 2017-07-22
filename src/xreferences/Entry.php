<?php
namespace pdflib\xreferences;

class Entry {
	private $offset;
	
	private $generation;
	
	private $used;
	
	private $reference;
	
	
	public function __construct($offset, $generation, $used, $reference){
		$this->offset		= $offset;
		$this->generation	= $generation;
		$this->used			= $used;
		$this->reference	= $reference;
	}
}