<?php
namespace pdflib\structure;


class Page {
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
}