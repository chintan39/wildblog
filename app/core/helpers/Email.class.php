<?php

/**
 * Class Email is an extention to PHPMailer class (logging is added).
 * This class handles sending emails with logging.
 * @var <boolean> $logStoring Default set to true
 */

require_once(DIR_LIBS . 'PHPMailer/class.phpmailer.php');

class Email extends PHPMailer {

	private $logStoring       = true;
	var $logId = null;
	
	/**
	 * Constructor
	 * @param <array> $exceptions
	 */
	public function __construct($exceptions = false) {
		parent::__construct($exceptions);
		$this->SetFrom('noreply@' . str_replace('www.', '', strtolower($_SERVER['SERVER_NAME'])), tp("Project Title"), 0);
	}
	
	/**
	 * Sets log stroring option
	 */
	public function SetLogStoring($logStoring) {
		$this->logStoring = $logStoring;
	}
	
	/**
	 * returns addresses from the speicified property (from, copy, to) in string
	 * @return <string> Addresses
	 */
	private function getAddresses($property) {
		if (!preg_match('/^(From|to|cc|bcc|ReplyTo)$/', $property)) {
			echo 'Invalid property name: ' . $property;
			return false;
		}
		$addresses = array();
		
		foreach ($this->$property as $address) {
			$addresses[] = $address[0];
		}
		
		return implode(", ", $addresses);
	}
	
	
	public function Send() {
		$sent = parent::Send();
		
		if ($this->logStoring) {
			$emailLog = new BaseEmailLogModel();
			$emailLog->to = $this->getAddresses("to");
			$emailLog->cc = $this->getAddresses("cc");
			$emailLog->bcc = $this->getAddresses("bcc");
			$emailLog->from = $this->From;
			$emailLog->reply = $this->getAddresses("ReplyTo");
			$emailLog->subject = $this->Subject;
			$emailLog->alt_text = $this->AltBody;
			$emailLog->text = $this->Body;
			$emailLog->send_result = $sent;
			
			if (!$sent) {
				$emailLog->send_error = $this->ErrorInfo;
				// TODO: not clear how to handle the incorrect sending, 
				// callback function could be used
			} 
		
			$emailLog->Save();
			
			$this->logId = $emailLog->id;
		}
		
		return $sent;
	}
	
	/**
	 * Adds a "To" address.
	 * @param string $address an address or addresses separated by ',' or ';'
	 * @param string $name
	 * @return boolean true on success, false if address already used
	 */
	public function AddAddressMore($address, $name = '') {
		$res = true;
		
		// more addresses separated by ',' or ';'
		foreach (preg_split('/[,;]/', $address) as $addr) {
			$res &= $this->AddAddress($addr);
		}
		return $res;
	}
	
	/**
	 * Adds a "Cc" address.
	 * Note: this function works with the SMTP mailer on win32, not with the "mail" mailer.
	 * @param string $address an address or addresses separated by ',' or ';'
	 * @param string $name
	 * @return boolean true on success, false if address already used
	 */
	public function AddCCMore($address, $name = '') {
		$res = true;

		// more addresses separated by ',' or ';'
		foreach (preg_split('/[,;]/', $address) as $addr) {
			$res &= $this->AddCC($addr);
		}
		return $res;
	}
	
	/**
	 * Adds a "Bcc" address.
	 * Note: this function works with the SMTP mailer on win32, not with the "mail" mailer.
	 * @param string $address an address or addresses separated by ',' or ';'
	 * @param string $name
	 * @return boolean true on success, false if address already used
	 */
	public function AddBCCMore($address, $name = '') {
		$res = true;

		// more addresses separated by ',' or ';'
		foreach (preg_split('/[,;]/', $address) as $addr) {
			$res &= $this->AddBCC($addr);
		}
		return $res;
	}
	
	/**
	 * Adds a "Reply-to" address.
	 * @param string $address an address or addresses separated by ',' or ';'
	 * @param string $name
	 * @return boolean
	 */
	public function AddReplyToMore($address, $name = '') {
		$res = true;

		// more addresses separated by ',' or ';'
		foreach (preg_split('/[,;]/', $address) as $addr) {
			$res &= $this->AddReplyTo($addr);
		}
		return $res;
	}
	
}


?>
