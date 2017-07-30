<?php
namespace pdflib\structure;

use pdflib\datatypes\Name;
use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Reference;
use pdflib\datatypes\Referenceable;

class ResourceManager {
	/**
	 *
	 * @var \pdflib\xreferences\FileIO
	 */
	private $io;
	
	/**
	 * 
	 * @var array
	 */
	private $fonts;
	
	/**
	 * 
	 * @param \pdflib\xreferences\FileIO $io
	 */
	public function __construct($io){
		$this->io		= $io;
		$this->fonts	= [];
		
		$reference = $this->io->getValue('Root');
		if($reference){
			$root = $this->io->getIndirect($reference)->getObject();
			$reference = $root->get('Pages');
			if($reference){
				$pages = $this->io->getIndirect($reference)->getObject();
				if($pages->get('Resources')) $this->extract($pages->get('Resources'));
				$this->search($pages);
			}
		}
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
	
	
	
	private function search($branch){
		foreach($branch->get('Kids') as $reference){
			$child = $this->io->getIndirect($reference)->getObject();
			if($child->get('Resources')) $this->extract($child->get('Resources'));
			if($child->get('Kids')) $this->search($child);
		}
	}
	
	private function extract($resources){
		if($resources instanceof Referenceable){
			$this->extract($this->io->getIndirect($resources)->getObject());
		}else{
			echo $resources->output()."\n";
			$fonts = $resources->get('Font');
			if($fonts){
				foreach ($fonts as $localName=>$reference){
					$font = $this->getByReference($reference);
					if(!$font){
						$descriptor = $this->io->getIndirect($reference)->getObject();
						
						$font = new \stdClass();
						$font->name			= "{$descriptor->get('BaseFont')}";
						$font->localNames	= [];
						$font->reference	= $reference;
						$this->fonts[] = $font;
					}
					
					$hasName = false;
					foreach($font->localNames as $name){
						if($name == "$localName"){
							$hasName = true;
							break;
						}
					}
					
					if(!$hasName){
						$font->localNames[] = $localName;
					}
				}
			}
		}
	}
}