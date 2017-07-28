<?php
namespace pdflib\structure;

use pdflib\datatypes\Referenceable;

class Font implements Referenceable {
	/**
	 *
	 * @var \pdflib\datatypes\Referenceable
	 */
	private $reference;
	
	/**
	 * 
	 * @var \pdflib\datatypes\Name
	 */
	private $localName;
	
	/**
	 * 
	 * @var number
	 */
	private $size;
	
	/**
	 *
	 * @param \pdflib\xreferences\FileIO $io
	 * @param \pdflib\datatypes\Indirect $data
	 */
	public function __construct($reference, $localName, $size){
		$this->reference	= $reference;
		$this->localName	= $localName;
		$this->size			= $size;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \pdflib\datatypes\Referenceable::getNumber()
	 */
	public function getNumber(){
		return $this->reference->getNumber();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \pdflib\datatypes\Referenceable::getGeneration()
	 */
	public function getGeneration(){
		return $this->reference->getGeneration();
	}
	
	/**
	 * 
	 * @return \pdflib\datatypes\Name
	 */
	public function getLocalName(){
		return $this->localName;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getSize(){
		return $this->size;
	}
}