<?php
namespace pdflib\structure;

use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Name;
use pdflib\datatypes\Collection;
use pdflib\datatypes\Number;

class Catalog {
	/**
	 *
	 * @var \pdflib\xreferences\FileIO
	 */
	private $io;
	
	/**
	 *
	 * @param \pdflib\xreferences\FileIO $io
	 */
	public function __construct($io){
		$this->io	= $io;
	}
	
	/**
	 * 
	 * @param \pdflib\structure\Page $before
	 * @return \pdflib\structure\Page
	 */
	public function addPage($before = null){
		$root = $this->getRoot();
		
		$reference = $root->get('Pages');
		if(!$reference){
			$object = new Dictionary();
			$object->set('Type', new Name('Pages'));
			$object->set('Kids', new Collection());
			$object->set('Count', new Number(0));
			$reference = $this->io->allocate($object);
			$root->set('Pages', $reference);
		}
		$pages = $this->io->getIndirect($reference)->getObject();
		
		$object = new Dictionary();
		$object->set('Type', new Name('Page'));
		$object->set('Parent', $reference);
		$object->set('Resources', new Dictionary());
		
		$object->set('MediaBox', $mediaBox = new Collection());
			$mediaBox->push(new Number(0));
			$mediaBox->push(new Number(0));
			$mediaBox->push(new Number(300));
			$mediaBox->push(new Number(400));
		
		
		$reference = $this->io->allocate($object);
		$pages->get('Kids')->push($reference);
		
		$pages->set('Count', new Number($pages->get('Count')->getValue() + 1));
		
		return new Page($this->io, $this->io->getIndirect($reference)->getObject());
	}
	
	/**
	 * 
	 * @param integer $index
	 * @return \pdflib\structure\Page
	 */
	public function getPage($index){
		return new Page();
	}
	
	/**
	 * 
	 * @return \pdflib\datatypes\Dictionary
	 */
	private function getRoot(){
		$reference = $this->io->getValue('Root');
		
		
		if(!$reference){
			$object = new Dictionary();
			$object->set('Type', new Name('Catalog'));
			$reference = $this->io->allocate($object);
			$this->io->setValue('Root', $reference);
		}
		
		return $this->io->getIndirect($reference)->getObject();
	}
}