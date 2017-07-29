<?php
namespace pdflib\structure;

use pdflib\datatypes\Name;
use pdflib\datatypes\Dictionary;

class ResourceManager {
	/**
	 *
	 * @var \pdflib\xreferences\FileIO
	 */
	private $io;
	
	private $fonts;
	
	/**
	 * 
	 * @param \pdflib\xreferences\FileIO $io
	 */
	public function __construct($io){
		$this->io		= $io;
		$this->fonts	= [];
	}
	
	/**
	 * 
	 * @param string $name
	 */
	public function getFont($name){
		foreach($this->fonts as $font){
			if($font->name == $name){
				return $font->reference;
			}
		}
		
		$font = new \stdClass();
		$font->name			= $name;
		$font->localNames	= [];
		
		$object = new Dictionary();
		$object->set('Type', new Name('Font'));
		$object->set('BaseFont', new Name($name));
		$object->set('Subtype', new Name('Type1'));
		$object->set('Encoding', new Name('WinAnsiEncoding'));
		$font->reference = $this->io->allocate($object);
		$this->fonts[] = $font;
		
		return $font->reference;
	}
	
	/**
	 * 
	 * @param  \pdflib\datatypes\Dictionary $dictionary
	 * @param  \pdflib\datatypes\Referenceable $reference
	 * @return \pdflib\datatypes\Name
	 */
	public function getFontLocalName($dictionary, $reference){
		// Check if the dictionary already contains the reference
		foreach($dictionary as $localName=>$value){
			if($value->getNumber() == $reference->getNumber() && $value->getGeneration() == $reference->getGeneration()){
				return $localName;
			}
		}
		
		$font = $this->getByReference($reference);
		// Lets see if the already used names can be used
		foreach($font->localNames as $localName){
			if(!$dictionary->get($localName)){
				$dictionary->set($localName, $reference);
				return $localName;
			}
		}
		
		// if all else fails generate new name
		$index = count($this->fonts);
		do {
			if(!$dictionary->get('F'.$index)){
				$localName = new Name('F'.$index);
				$dictionary->set($localName, $reference);
				$font->localNames[] = $localName;
				return $localName;
			}
		}while($index++ < 100);
		
		
		return new Name('');
	}
	
	/**
	 * 
	 * @param  \pdflib\datatypes\Referenceable $reference
	 * @return \stdClass|boolean
	 */
	private function getByReference($reference){
		foreach($this->fonts as $font){
			if($font->reference->getNumber() == $reference->getNumber() && $font->reference->getGeneration() == $reference->getGeneration()){
				return $font;
			}
		}
		return false;
	}
}