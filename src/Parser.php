<?php
namespace pdflib;

use pdflib\xreferences\Table;
use pdflib\datatypes\Dictionary;

class Parser {
	/**
	 * 
	 * @param \pdflib\Handle $handle
	 * @return \pdflib\xreferences\Table
	 */
	public static function readTable($handle){
		if($handle->readline() != 'xref') throw new \Exception('Not an xref table');
		$table = new Table();
		
		while(($line = $handle->readline())!==false){
			if(preg_match('/^(\d+)\s(\d+)$/', $line, $matches)){
				$section	= $table->addSection($matches[1]);
				$length		= (int)$matches[2];
				
				for($index = 0; $index < $length; $index++){
					if(($line = $handle->readline())===false) throw new \Exception('Unexpected end of xref');
					if(!preg_match('/^(\d{10})\s(\d{5})\s(f|n)\s*$/', $line, $matches)) throw new \Exception('Unexpected "'.$line.'" expected a xref section entry');
					
					$section->add((int)ltrim($matches[1]), (int)ltrim($matches[2]), $matches[3]=='n');
				}
			}else if($line=='trailer'){
				$dictionary = self::readObject($handle);
				if(!$dictionary instanceof Dictionary) throw new \Exception('Expected object expected Dictionary');
				
				foreach($dictionary as $key=>$value){
					$table->getDictionary()->set($key, $value);
				}
				
				break;
			}else{
				throw new \Exception('Unexpected "'.$line.'" expected a xref line');
			}
		}
		
		return $table;
	}
	
	/**
	 * 
	 * @param \pdflib\Handle $handle
	 * @return \pdflib\datatypes\Object
	 */
	public static function readObject($handle){
		echo $handle->readline();
		return new Dictionary();
	}
}