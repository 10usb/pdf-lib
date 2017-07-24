<?php
namespace pdflib\xreferences;

class Entry {
	/**
	 * The offset where the indirect object can be found
	 * @var integer
	 */
	private $offset;
	
	/**
	 * The number where the next free entry can be found
	 * @var integer
	 */
	private $generation;
	
	/**
	 * If this entry is free or used
	 * @var boolean
	 */
	private $used;
	
	/**
	 * The reference object of this entry when loaded in memory otherwise null
	 * @var \pdflib\datatypes\Reference|null
	 */
	private $reference;
	
	/**
	 * 
	 * @param integer $offset
	 * @param integer $generation
	 * @param boolean $used
	 * @param \pdflib\datatypes\Reference|null $reference
	 */
	public function __construct($offset, $generation, $used, $reference){
		$this->offset		= $offset;
		$this->generation	= $generation;
		$this->used			= $used;
		$this->reference	= $reference;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getOffset(){
		return $this->offset;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getGeneration(){
		return $this->generation;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function isUsed(){
		return $this->used;
	}
	
	/**
	 * 
	 * @return \pdflib\datatypes\Reference|null
	 */
	public function getReference(){
		return $this->reference;
	}
	
	/**
	 * Writes the object data to the stream and moves the offset to the end of 
	 * @param \pdflib\Handle $handle
	 */
	public function flush($handle){
		// Not goog enough :'(
		if($this->offset > 0) return false;
		if(!$this->reference) return false;
		
		
		$handle->seek($handle->getOffset());
		
		$this->offset = $handle->tell();
		$handle->writeline(sprintf('%d %d obj', $this->reference->getNumber(), $this->reference->getGeneration()));
		$handle->writeline($this->reference->getBody());
		$handle->writeline('endobj');
		$handle->writeline('');
		$handle->setOffset($handle->tell());
	}
}