<?php
namespace ASDRUBAEL\TO_XML;

use mysql_xdevapi\Exception;

class ArrayToXML{
	
	private $struct = [];
	private $path = null;
	private $xml = null;
	
	function __construct(array $arr, $path = null){
	
		if($path === null){
			throw new \Exception('path empty');
		}
		else{
			$this->path = $path;
		}

		$this->struct = $arr;
		$this->xml = new \XMLWriter();
		$this->xml->openMemory();
		$this->xml->startDocument("1.0", "UTF-8");
		$this->addStruct( $this->struct );
		$this->log = $this->xml->outputMemory();
		
		$this->writeToFile();
	}
	
	private function addStruct( $elem ){
		
		//var_dump($elem['child'][0]);
		
		if(!$this->checkParam($elem)){
			var_dump( $elem );
		}

		$this->xml->startElement($elem['name']);
			
			if( !$this->isEmpty($elem) ){
				if( isset($elem['attr']) && count($elem['attr']) ){
				
					foreach($elem['attr'] as $attrName => $attrVal){
						$this->xml->startAttribute( $attrName );
							$this->xml->text( $attrVal );
						$this->xml->endAttribute();
					}
				}
				if( !empty($elem['text']) ){
					$this->xml->text($elem['text']);
				}
				
				if( isset($elem['child']) && count($elem['child']) ){
				
					foreach($elem['child'] as $item){
						$this->addStruct($item);
					}
				}
			}
			else{
				$this->xml->text(" ");
			}
			
		$this->xml->endElement();
	}
	
	private function isEmpty($elem){
		
		if( !(isset($elem['attr']) && count($elem['attr'])) && empty($elem['text']) && !(isset($elem['child']) && count($elem['child'])) ){
			return true;
		}
		return false;
	}
	
	public function writeToFile(){
		
		if( !empty($this->log) ){
			$writeRes = file_put_contents($this->path, $this->log);
			
			if($writeRes === false){
				return false;
			}
			return true;
		}
		return false;
	}
	
	private function checkParam($elem){
		
		if(empty($elem['name'])){
			return false;
		}
		//if(empty($elem['text'])){
		//	return false;
		//}
		return true;
	}
}