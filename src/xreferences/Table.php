<?php
namespace pdflib\xreferences;

use pdflib\datatypes\Dictionary;

class Table {
	
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
	 */
	public function __construct(){
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
	
	/**
	 * 
	 * @return \pdflib\datatypes\Dictionary
	 */
	public function getDictionary(){
		return $this->dictionary;
	}
}