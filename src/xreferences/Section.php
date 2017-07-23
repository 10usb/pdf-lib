<?php
namespace pdflib\xreferences;

class Section {
	/**
	 * 
	 * @var integer
	 */
	private $number;
	
	/**
	 * 
	 * @var \pdflib\xreferences\Entry[]
	 */
	private $entries;
	
	/**
	 * 
	 * @param integer $number
	 */
	public function __construct($number){
		$this->number = $number;
		$this->entries = [];
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getNumber(){
		return $this->number;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getSize(){
		return count($this->entries);
	}
	
	/**
	 * 
	 * @param integer $offset
	 * @param integer $generation
	 * @param boolean $used
	 * @param  \pdflib\datatypes\Reference|null $reference
	 * @return \pdflib\xreferences\Entry
	 */
	public function add($offset, $generation, $used, $reference = null){
		$entry = new Entry($offset, $generation, $used, $reference);
		$this->entries[] = $entry;
		return $entry;
	}
	
	/**
	 * 
	 * @return \pdflib\xreferences\Entry[]
	 */
	public function getEntries(){
		return $this->entries;
	}
	
	/**
	 *
	 * @param \pdflib\Handle $handle
	 */
	public function flush($handle){
		foreach($this->entries as $index=>$entry){
			$entry->flush($handle,$this->number + $index);
		}
	}
}