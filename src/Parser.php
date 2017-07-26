<?php
namespace pdflib;

use pdflib\xreferences\Table;
use pdflib\datatypes\Dictionary;
use pdflib\datatypes\Name;
use pdflib\datatypes\Reference;

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
				$dictionary = self::readObject($handle, $handle->readline());
				if(!$dictionary instanceof Dictionary) throw new \Exception('Unexpected object expected Dictionary');
				
				foreach($dictionary->getEntries() as $entry){
					$table->getDictionary()->set($entry->key, $entry->value);
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
	public static function readObject($handle, $buffer, &$offset = 0){
		if(preg_match('/^\<\<\\s*/', substr($buffer, $offset), $matches)){
			$offset+= strlen($matches[0]);
			
			$dictionary = new Dictionary();
			
			while(!preg_match('/^\>\>/', substr($buffer, $offset), $matches)){
				$key = self::readObject($handle, $buffer, $offset);
				if(!$key instanceof Name) throw new \Exception('Unexpected object expected Name');
				
				$value = self::readObject($handle, $buffer, $offset);
				
				$dictionary->set($key, $value);
			}
			
			return $dictionary;
		}elseif(preg_match('/^\/([^\s\(\)\<\>\[\]\{\}\/\%\#]+)\s*/', substr($buffer, $offset), $matches)){
			$offset+= strlen($matches[0]);
			return new Name(preg_replace_callback('/#[0-9a-f]{2}/i', function($matches){
				return chr(hexdec($matches[0]));
			}, $matches[1]));
		}elseif(preg_match('/^(\d+) (\d+) R/', substr($buffer, $offset), $matches)){
			$offset+= strlen($matches[0]);
			
			return new Reference($matches[1], $matches[2]);
		}
		
		echo ":(\n";
		echo substr($buffer, $offset);
		exit;
		//$line = $handle->readline();
		//echo $handle->readline();
		return new Dictionary();
	}
}