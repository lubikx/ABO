<?php 

class abo_item {
	private $amount;
	private $variable_sym = 0;
	private $bank = 0;
	private $account_number = 0;
	private $account_pre = 0;
	private $spec_sym = null;
	private $const_sym = 0;
	
	private $dest_account = 0;
	private $dest_account_pre = 0;
	
	private $message = '';
	
	public function __construct($full_account_number, $amount, $variable_sym = 0){
		$this->setAmount($amount)->setAccount($full_account_number)->setVarSym($variable_sym);
	}
	
	/**
	 * 
	 * Set the amount to transfer
	 * @param float $float
	 * @param boolean $halere amount is in halere
	 */
	public function setAmount($amount, $halere = false){
		$this->amount = $amount;
		if(!$halere) $this->amount *= 100;
		return $this;
	}
	
	public function getAmount(){
		return $this->amount;
	}
	
	/**
	 *
	 
	 * @param string $account - account in format (xxxx-)xxxxxxxx/xxxx
	 */
	public function setAccount($account){
		$account = explode('/',$account);
		$this->bank = $account[1];
		if(strpos($account[0], '-')!==false){
			$number = explode('-', $account[0]);
			$this->account_pre = $number[0];
			$this->account_number = $number[1];
		}  else {
			$this->account_number = $account[0];
		}
		
		return $this;
		
	}
	
	/**
	 * 
	 * Set the destination accound
	 * @param string $account in format (xxxx-)xxxxxx/xxxx
	 */
	public function setDestAccount($account){
		$account = explode('/',$account);
		//$this->bank = $account[1]; //ba
		if(strpos($account[0], '-')!==false){
			$number = explode('-', $account[0]);
			$this->dest_account_pre = $number[0];
			$this->dest_account = $number[1];
		}  else {
			$this->account_number = $account[0];
		}
		
		return $this;
		
	}
	
	public function  setVarSym($varSym){
		$this->variable_sym = $varSym;
		return $this;
	}
	
	public function  setConstSym($constSym){
		$this->const_sym = $constSym;
		return $this;
	}
	
	public function  setSpecSym($specSym){
		$this->spec_sym = $specSym;
		return $this;
	}
	
	public function  setMessage($message){
		if(is_array($message)){
			$message = implode(' AV|', $message);
		}
		$this->message = $message;
		return $this;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param boolean $supress_number if the destination nubmer is in the group header
	 * @return string
	 */
	public function generate($supress_number = true){
		$res = "";
		if(!$supress_number) {
			$res .= abo::account($this->dest_account,$this->dest_account_pre)." ";
		}
		$res .= sprintf("%s %d %s %s%04d ", abo::account($this->account_number,$this->account_pre), $this->amount, $this->variable_sym, $this->bank, $this->const_sym);
		
		$res .= ($this->spec_sym ? $this->spec_sym : ' ').' ';
		$res .= ($this->message ? mb_substr('AV:'.$this->message, 0,38) : ' ');
		$res.="\r\n";
		
		return $res;
		
	}
	
	
}
