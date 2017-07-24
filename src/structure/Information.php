<?php
namespace pdflib\structure;

use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Text;

class Information {
	/**
	 * 
	 * @var \pdflib\xreferences\Table
	 */
	private $table;
	
	/**
	 * 
	 * @param \pdflib\xreferences\Table $table
	 */
	public function __construct($table){
		$this->table = $table;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getTitle(){
		return $this->getValue('Title');
	}
	
	/**
	 * 
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setTitle($value){
		$this->setValue('Title', new Text($value));
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getAuthor(){
		return $this->getValue('Author');
	}
	
	/**
	 *
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setAuthor($value){
		$this->setValue('Author', new Text($value));
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getSubject(){
		return $this->getValue('Subject');
	}
	
	/**
	 *
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setSubject($value){
		$this->setValue('Subject', new Text($value));
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getKeywords(){
		return $this->getValue('Keywords');
	}
	
	/**
	 *
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setKeywords($value){
		$this->setValue('Keywords', new Text($value));
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getCreator(){
		return $this->getValue('Creator');
	}
	
	/**
	 *
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setCreator($value){
		$this->setValue('Creator', new Text($value));
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getProducer(){
		return $this->getValue('Producer');
	}
	
	/**
	 *
	 * @param string $value
	 * @return \pdflib\structure\Information
	 */
	public function setProducer($value){
		$this->setValue('Producer', new Text($value));
		return $this;
	}
	
	/**
	 * 
	 * @return \DateTime
	 */
	public function getCreated(){
		return '';
	}
	
	/**
	 *
	 * @param \DateTime $value
	 * @return \pdflib\structure\Information
	 */
	public function setCreated($value){
		$this->setValue('CreationDate', new Text('D:'.substr($value->format('YmdHisO'), 0, -2)."'".substr($value->format('O'), -2)."'"));
		return $this;
	}
	
	/**
	 * 
	 * @return \DateTime
	 */
	public function getModDate(){
		return '';
	}
	
	/**
	 *
	 * @param \DateTime $value
	 */
	public function setModified($value){
		$this->setValue('ModDate', new Text('D:'.substr($value->format('YmdHisO'), 0, -2)."'".substr($value->format('O'), -2)."'"));
		return $this;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return string
	 */
	private function getValue($name){
		return '';
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $value
	 */
	private function setValue($name, $value){
		$reference = $this->table->getDictionary()->get('Info');
		
		if(!$reference){
			$reference = $this->table->allocate(new Dictionary());
			$this->table->getDictionary()->set('Info', $reference);
		}
		$reference->getObject()->set($name, $value);
	}
}