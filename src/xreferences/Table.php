<?php
namespace pdflib\xreferences;

use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Reference;
use pdflib\datatypes\Indirect;
use pdflib\datatypes\Referenceable;

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
		$this->dictionary	= null;
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
	 * @param \pdflib\references\Table $previous
	 */
	public function setPrevious($previous){
		$this->previous = $previous;
	}
	
	/**
	 * 
	 * @return \pdflib\references\Table
	 */
	public function getPrevious(){
		return $this->previous;
	}
	
	/**
	 * 
	 * @return \pdflib\datatypes\Dictionary
	 */
	public function getDictionary(){
		if(!$this->dictionary){
			if($this->previous){
				$this->dictionary = clone $this->previous->getDictionary();
			}else{
				$this->dictionary = new Dictionary();
			}
		}
		return $this->dictionary;
	}
	
	public function getModifications(&$modifications = []){
		foreach($this->sections as $section){
			foreach($section->getEntries() as $entry){
				if($entry->isModified()) $modifications[] = $entry->getIndirect(null);
			}
		}
		if($this->previous){
			$this->previous->getModifications($modifications);
		}
		
		return $modifications;
	}
	
	/**
	 * 
	 * @param \pdflib\Handle $handle
	 */
	public function flush($handle){
		if($this->previous){
			$modifications = $this->previous->getModifications();
			
			foreach($modifications as $modification){
				$exists = false;
				foreach($this->sections as $section){
					if($section->contains($modification)){
						$exists = true;
					}
				}
				
				if(!$exists){
					$appended = false;
					foreach($this->sections as $section){
						if($section->canAppend($modification)){
							$section->add(0, $modification->getGeneration(), true, $modification);
							$appended = true;
						}
					}
					
					if(!$appended){
						$this->addSection($modification->getNumber())->add(0, $modification->getGeneration(), true, $modification);
					}
				}
				
			}
		}
		
		foreach($this->sections as $section){
			$section->flush($handle);
		}
		
		$handle->seek($handle->getOffset());
		$startxref = $handle->tell();
		$handle->writeline('xref');
		foreach($this->sections as $section){
			$handle->writeline(sprintf('%d %d', $section->getNumber(), $section->getSize()));
			foreach($section->getEntries() as $entry){
				$handle->writeline(substr(
											sprintf('%010d %05d %s  ', $entry->getOffset(), $entry->getGeneration(), $entry->isUsed() ? 'n' : 'f'),
											0,
											20 - strlen($handle->getLineEnding())
										));
			}
		}
		$handle->writeline('trailer');
		$handle->writeline($this->getDictionary()->output());
		$handle->writeline('startxref');
		$handle->writeline($startxref);
		$handle->write('%%EOF');
	}
	
	/**
	 * 
	 * @param \pdflib\datatypes\Object $object
	 */
	public function allocate($object){
		$section = $this->sections[0];
		
		$indirect = new Indirect($section->getNumber() + $section->getSize(), 0, $object);
		$this->sections[0]->add(0, $indirect->getGeneration(), true, $indirect);
		
		return new Reference($indirect->getNumber(), $indirect->getGeneration());
	}
	
	/**
	 * 
	 * @param \pdflib\datatypes\Referenceable $reference
	 */
	public function getIndirect($handle, $reference){
		if(!$reference instanceof Referenceable) throw new \Exception('Unexpected value expected Reference');
		
		foreach($this->sections as $section){
			if($section->contains($reference)){
				return $section->getIndirect($handle, $reference);
			}
		}
		
		if($this->previous){
			return $this->previous->getIndirect($handle, $reference);
		}
		return null;
	}
}