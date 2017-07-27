<?php
namespace pdflib\structure;


class Page {
	public function getCanvas(){
		return new Canvas();
	}
}