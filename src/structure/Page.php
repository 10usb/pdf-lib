<?php
namespace pdflib\structure;

use pdflib\datatypes\Referenceable;
use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Name;

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
	 * @param \pdflib\xreferences\FileIO $io
	 * @param \pdflib\datatypes\Indirect $data
	 */
	public function __construct($io, $indirect){
		$this->io		= $io;
		$this->indirect	= $indirect;
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
		// TODO search already used fonts
		// TODO search cache file cache
		// TODO search catalog
		
		$object = new Dictionary();
		$object->set('Type', new Name('Font'));
		$object->set('BaseFont', new Name($name));
		$object->set('Subtype', new Name('Type1'));
		$object->set('Encoding', new Name('WinAnsiEncoding'));
		$reference = $this->io->allocate($object);
		
		$resources = $this->indirect->getObject()->get('Resources');
		if(!$font = $resources->get('Font')){
			$resources->set('Font', $font = new Dictionary());
		}
		
		$index = 1;
		do {
			if(!$font->get('F'.$index)){
				$localName = new Name('F'.$index);
				$font->set($localName, $reference);
				return new Font($reference, $localName, $size);
			}
		}while($index++ < 100);
		
		throw new \Exception('Failed to create a local name for the font');
	}
}