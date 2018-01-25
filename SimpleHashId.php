<?php

/**
 * SimpleHashId
 *
 * @package	CodeIgniter
 * @author	Simon Dion (simmoniz)
 * @link	https://github.com/Simmoniz/simplehashid
 * @since	Version 1
 */
class SimpleHashId{

	private $chars;
	private $hash;
	private $salt;
	private $minChars;
	private $len;
	private $break_character; // character used to breack the hash
	private $bit_table;

	function __construct($minChars=5, $salt='', $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'){
		
		$this->chars = $chars;
		if(!$salt){
			$ci = &get_instance();
			$salt = $ci->config->item('encryption_key');	
		}
		$this->salt = $salt;
		$this->minChars = $minChars;
		$this->len = strlen($this->chars) - 1;
		$this->bit_table = array();
		$this->generateHash();
		
	}
	
	private function generateHash(){
		$hash = $this->chars;
		for($i=0; $i<strlen($this->salt); $i++){
			$c = $this->salt[$i];
			$h = '';
			$j = 0;
			while($hash){
				$pos = $i%2?0:strlen($hash) - 1;
				if($hash[$pos]==$c){
					$h.=$hash;
					$hash='';	
				}else{
					if($j%2)$h = $hash[$pos].$h;
					else $h .= $hash[$pos];
					$hash = $pos==0 ? substr($hash, 1) : substr($hash, 0, $pos);
				}
				$j++;
				if($j==999)die('OOPS');
			}
			$hash = $h;
		}
		$this->break_character = substr($hash, strlen($hash)-1);
		$this->hash = substr($hash, 0, strlen($hash)-1);
		for($i = 0; $i<$this->len; $i++)$this->bit_table[$this->hash[$i]] = pow(2, $i);
	}
	
	
	public function encode($id){
		
		if( $id >= pow(2, $this->len) )return NULL; // cannot generate hash !!
		
		$hash = '';
		$bit = 0;
		
		while($id){
			if($id%2)$hash .= $this->hash[$bit];
			$id = $id >> 1;
			$bit++;
		}
		if(strlen($hash)<$this->minChars){
			$hash .= $this->break_character;
			if(strlen($hash)<$this->minChars){
				for($i=strlen($hash); $i<=$this->minChars; $i++){
					$hash .= substr($this->hash, rand(0, $this->len-1),1);				
				}		
			}
		}
		return $hash;
	}
	
	public function decode($hash){
		$val = 0;
		for($i = 0 ; $i < strlen($hash); $i++){
			if($hash[$i]==$this->break_character)break;
			$val += $this->bit_table[$hash[$i]];
		}
		return $val;
	}
	
	public function stats(){
		return array(	'len' => $this->len,
						'min' => 0,
						'max' => pow(2, $this->len),
						'generated_hash' => $this->hash,
						'break_character' => $this->break_character);	
	}
	
}