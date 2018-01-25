<?php

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

	function __construct($minChars=5, $salt='salt', $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'){
		
		$this->chars = $chars;
		$this->salt = $salt;
		$this->minChars = $minChars;
		
		$this->bit_table = array();
		$this->generateHash();
		
	}
	
	private function generateHash(){
		
		// normalize character table
		for($i=0; $i<strlen($this->chars); $i++){
			for($j=$i+1; $j < strlen($this->chars); $j++){
				if($this->chars[$i]==$this->chars[$j]){
					$this->chars = substr($this->chars, 0, $j) . substr($this->chars, $j+1);
					$j--;
				}
			}
		}
		
		$hash = $this->chars;
		$this->len = strlen($this->chars) - 1;
		
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
			}
			$hash = $h;
		}
		$this->break_character = substr($hash, strlen($hash)-1);
		$this->hash = substr($hash, 0, strlen($hash)-1);
		
		for($i = 0; $i<$this->len; $i++)$this->bit_table[$this->hash[$i]] = pow(2, $i);
	}
	
	
	public function encode($id){
		
		$toencode = $id;
		
		if( $toencode >= pow(2, $this->len) )return NULL; // cannot generate hash !!
		
		$hash = '';
		$bit = 0;
		
		while($toencode){
			if($toencode%2)$hash .= $this->hash[$bit];
			$toencode = $toencode >> 1;
			$bit++;
		}
		
		if(strlen($hash)<$this->minChars){
			$hash .= $this->break_character;
			// generate some random padding
			if(strlen($hash)<$this->minChars){
				$p = $id;
				for($i=strlen($hash); $i<=$this->minChars; $i++){
					$p = $p % $this->len;
					$hash .= substr($this->hash, $p,1);				
					$p += $p * ($p+1);
				}		
			}
		}
		return $hash;
	}
	
	public function decode($hash){
		$val = 0;
		for($i = 0 ; $i < strlen($hash); $i++){
			if($hash[$i]==$this->break_character)break;
			if(!array_key_exists($hash[$i], $this->bit_table))return NULL;
			$val += $this->bit_table[$hash[$i]];
		}
		return $val;
	}
	
	public function stats(){
		return array(	'character_table' => $this->chars,
						'len' => $this->len,
						'min' => 0,
						'max' => pow(2, $this->len),
						'generated_hash' => $this->hash,
						'break_character' => $this->break_character);	
	}
	
}
