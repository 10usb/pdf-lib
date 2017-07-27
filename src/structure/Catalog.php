<?php
namespace pdflib\structure;

class Catalog {
	/**
	 * 
	 * @param \pdflib\structure\Page $before
	 * @return \pdflib\structure\Page
	 */
	public function addPage($before = null){
		return new Page();
	}
	
	/**
	 * 
	 * @param integer $index
	 * @return \pdflib\structure\Page
	 */
	public function getPage($index){
		return new Page();
	}
}