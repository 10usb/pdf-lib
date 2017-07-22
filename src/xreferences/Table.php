<?php
namespace pdflib\xreferences;

use pdflib\datatypes\Dictionary;

class Table {
	/**
	 * 
	 * @var \pdflib\File
	 */
	private $file;
	
	/**
	 * 
	 * @var \pdflib\datatypes\Dictionary
	 */
	private $dictionary;
	
	/**
	 *
	 * @var \pdflib\references\Section
	 */
	private $sections;
	
	/**
	 * 
	 * @var \pdflib\references\Table
	 */
	private $previous;
	
	/**
	 * 
	 * @param \pdflib\File $file
	 */
	public function __construct($file){
		$this->file			= $file;
		$this->dictionary	= new Dictionary();
		$this->sections		= [];
		$this->previous		= null;
	}
	
	/**
	 * 
	 * @param numver $number
	 * @return \pdflib\xreferences\Section
	 */
	public function addSection($number){
		$section = new Section($number);
		$this->sections[] = $section;
		return $section;
	}
}