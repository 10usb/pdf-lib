<?php
namespace pdflib\structure;

use pdflib\datatypes\Referenceable;
use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Name;
use pdflib\datatypes\Reference;

class Page implements Referenceable {
	/**
	 *
	 * @var \pdflib\xreferences\FileIO
	 */
	private $io;
	/**
	 *
	 * @var \pdflib\datatypes\Indirect
	 */
	private $indirect;
	
	/**
	 *
	 * @var \pdflib\structure\ResourceManager
	 */
	private $resourceManager;
	
	/**
	 * 
	 * @param \pdflib\xreferences\FileIO $io
	 * @param \pdflib\datatypes\Indirect $data
	 * @param \pdflib\structure\ResourceManager $resourceManager
	 */
	public function __construct($io, $indirect, $resourceManager){
		$this->io				= $io;
		$this->indirect			= $indirect;
		$this->resourceManager	= $resourceManager;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \pdflib\datatypes\Referenceable::getNumber()
	 */
	public function getNumber(){
		return $this->indirect->getNumber();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \pdflib\datatypes\Referenceable::getGeneration()
	 */
	public function getGeneration(){
		return $this->indirect->getGeneration();
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getWidth(){
		$box = $this->indirect->getObject()->get('MediaBox');
		return $box->get(2)->getValue();
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getHeight(){
		$box = $this->indirect->getObject()->get('MediaBox');
		return $box->get(3)->getValue();
	}
	
	/**
	 * TODO make it compatible with contents being an array
	 * @return \pdflib\structure\Canvas
	 */
	public function getCanvas(){
		$reference = $this->indirect->getObject()->get('Contents');
		if(!$reference){
			$reference = $this->io->allocateStream();
			$this->indirect->getObject()->set('Contents', $reference);
		}
		
		$stream = $this->io->getIndirect($reference);
		
		return new Canvas($this->getWidth(), $this->getHeight(), $stream);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param number $size
	 * @return \pdflib\structure\Font
	 */
	public function getFont($name, $size){
		$resources = $this->indirect->getObject()->get('Resources');
		if(!$dictionary = $resources->get('Font')){
			$resources->set('Font', $dictionary= new Dictionary());
		}
		
		$reference = $this->resourceManager->getFont($name);
		$localName = $this->resourceManager->getFontLocalName($dictionary, $reference);
		
		return new Font($reference, $localName, $size);
	}
}