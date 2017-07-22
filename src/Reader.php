<?php
namespace pdflib;

class Reader {
	public static function readTable($handle, $offset){
		$handle->seek($offset);
		echo $handle->readline();
	}
}