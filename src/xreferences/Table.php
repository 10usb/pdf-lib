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
	 * @var \pdflib\references\Section[]
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
	 * @param integer $number
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
	
	/**
	 * 
	 * @param \pdflib\Handle $handle
	 */
	public function flush($handle){
		foreach($this->sections as $section){
			$section->flush($handle);
		}
		
		$handle->seek($handle->getOffset());
		$startxref = $handle->tell();
		$handle->writeline('xref');
		foreach($this->sections as $section){
			$handle->writeline(sprintf('%d %d', $section->getNumber(), $section->getSize()));
			foreach($section->getEntries() as $entry){
				$handle->writeline(substr(sprintf('%010d %05d %s  ', $entry->getOffset(), $entry->getGeneration(), $entry->isUsed() ? 'n' : 'f'), 0, 20 - strlen($handle->getLineEnding())));
			}
		}
		$handle->writeline('trailer');
		$handle->writeline($this->dictionary->output());
		$handle->writeline('startxref');
		$handle->writeline($startxref);
		$handle->write('%%EOF');
		
	}
}