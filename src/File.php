<?php
namespace pdflib;

use pdflib\xreferences\Table;

class File {
	/**
	 * Name of the file to be read from or written to
	 * @var string
	 */
	private $name;
	
	/**
	 *The handle of the file when an active stream is open
	 * @var resource
	 */
	private $handle;
	
	/**
	 * The offset in the file from where data is still mutable
	 * @var number
	 */
	private $offset;
	
	/**
	 *The reference table and subtables
	 * @var \pdflib\xreferences\Table
	 */
	private $xreference;
	
	/**
	 * 
	 * @param string $filename
	 */
	public function __construct($name = 'php://temp'){
		$this->name			= $name;
		$this->handle		= null;
		$this->offset		= 0;
		$this->xreference	= new Table();
		$this->xreference->addSection(0)->add(0, 65535, null);
	}
	
	/**
	 * Opens the stream
	 */
	public function load(){
		$this->handle = new Handle($this->name);
		$this->handle->seek(-28, true);
		if(!preg_match('/startxref(?:\r\n|\n|\r)(\d+)(\r\n|\n|\r)%%EOF/', $this->handle->read(28), $matches)) throw new \Exception('Failed to load file');
		$this->handle->setLineEnding($matches[2]);
		
		$this->xreference = Reader::readTable($this->handle, $matches[1]);
	}
	
	/**
	 * Closes the stream
	 */
	public function close(){
		$this->flush(true);
		$this->handle		= null;
		$this->xreference	= new Table();
	}
	
	/**
	 * Flushed all un committed data to the stream
	 * @param boolean $finalize Should the trailer be made permanent
	 */
	public function flush($finalize = false){
		if(!$this->handle) $this->handle = new Handle($this->name, true);
	}
	
	/**
	 * 
	 * @return resource
	 */
	public function getHandle(){
		return $this->handle->getHandle();
	}
	
	/**
	 * Returns the contents of the pdf file
	 * @return string
	 */
	public function getContents(){
		$this->flush();
		return $this->handle->getContents();
	}
}