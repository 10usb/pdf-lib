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
	 * @var \pdflib\xreferences\FileIO
	 */
	private $data;
	
	/**
	 * 
	 * @param \pdflib\xreferences\FileIO $io
	 * @param \pdflib\datatypes\Dictionary $data
	 */
	public function __construct($io, $data){
		$this->io	= $io;
		$this->data	= $data;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getWidth(){
		return 300;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getHeight(){
		return 400;
	}
	
	/**
	 * TODO make it compatible with contents being an array
	 * @return \pdflib\structure\Canvas
	 */
	public function getCanvas(){
		$reference = $this->data->get('Contents');
		if(!$reference){
			$reference = $this->io->allocateStream();
			$this->data->set('Contents', $reference);
		}
		
		$stream = $this->io->getIndirect($reference);
		
		return new Canvas($this->getWidth(), $this->getHeight(), $stream);
	}
}