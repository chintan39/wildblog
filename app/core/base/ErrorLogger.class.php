<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Handles errors catching and displaying inteligent messages.
 * Abstract class
 * Function:
 *   Before Config:
 *     Error reporting is set to report all Errors.
 *     Set error reporting to user function.
 *     Catch function and error reporting will call one function Log(EXCEPTION, $message);
 *     FatalError will output error message with AJAX.
 *     If Error is not Warning, Log(type, $message) will generate error message with AJAX.
 *     AJAX will send message to special script (similar to Log function).
 *   Ater Config:
 *     If DEBUG_MODE, restore original error handler, do not use AJAX, and use simple 
 *     Exception handling.
 */
class ErrorLogger {

	/* E-mail addresses to notify about error, can be separated using ',' */
	
	const ERR_EXCEPTION = 1;
	const ERR_ERROR = 2;
	const ERR_WARNING = 3;
	const ERR_FATAL_ERROR = 4;
	
	const CONFIG_FILE = 'error_handler_config.ini';
	const CSV_DELIMITER = "\t";
	
	static public $errorHandlerSet = 0;
	static public $config;
	static public $logFile;
	static public $logFileTmp;
	
	/**
	 * Initialize the ErrorLogger.
	 * Used in the beginning before Config is load.
	 */
	static public function init($handleAllErrors=true) {
		self::loadConfig();

		self::$logFile = DIR_PROJECT_PATH . self::$config['log_file'];
		self::$logFile = str_replace('[Y]', date('Y'), self::$logFile);
		self::$logFile = str_replace('[m]', date('m'), self::$logFile);
		self::$logFile = str_replace('[d]', date('d'), self::$logFile);
		self::$logFileTmp = DIR_PROJECT_PATH . self::$config['log_file_tmp'];
		if (isset($_GET['__display_error_page__'])) {
			self::displayErrorPage();
		}
		ini_set('display_errors', 'on');
		error_reporting(E_ALL);
		if (!self::$errorHandlerSet) {
			set_error_handler('wwErrorHandler');
			self::$errorHandlerSet = 1;
		}
		if (!Config::Get('DEBUG_MODE')) {
			self::initCatchingFatalErrors();
			if (!$handleAllErrors) {
				if (self::$errorHandlerSet) {
					restore_error_handler();
				}
				self::initCatchingFatalErrors(false);
			}
		}
	}

	
	/**
	 * Loads the error logging config from file.
	 */
	static public function loadConfig() {
		self::$config = parse_ini_file(DIR_PROJECT_CONFIG . self::CONFIG_FILE);
	}
	
	
	/**
	 * Handle exception
	 */
	static public function handleException($exception) {
		self::log(self::ERR_EXCEPTION, $exception->getMessage() . ': ' . $exception->getTraceAsString());
	}
	
	/**
	 * Log message and display error page if needed
	 */
	static public function log($type, $message) {
		self::storeMessage($type, $message, $_SERVER['REQUEST_URI']);
		if ($type != self::ERR_WARNING && (!class_exists('Config') || class_exists('Config') && !Config::Get('DEBUG_MODE'))) {
			self::displayErrorPage();
		} elseif (class_exists('Config') && Config::Get('DEBUG_MODE')) {
			echo "Error level $type: $message\n";
		}
	}
	
	/**
	 * Displays error page
	 */
	static public function displayErrorPage() {
		header("HTTP/1.0 500 Internal Server Error");
		echo self::getHTMLbegin();
		echo self::getHTMLend();
		die();
	}
	
	
	/**
	 * Add strings before and after fatal error. 
	 */
	static public function initCatchingFatalErrors($addStrings=true) {
		ini_set('error_prepend_string', ($addStrings ? self::getHTMLbegin(true) : ''));
		ini_set('error_append_string', ($addStrings ? self::getHTMLend(true) : ''));
	}
	
	
	/**
	 * Returns error type as string
	 */
	static public function getErrorTypeString($type) {
		switch ($type) {
			case self::ERR_EXCEPTION: return "EXCEP"; break;
			case self::ERR_WARNING: return "WARN"; break;
			case self::ERR_FATAL_ERROR: return "FATAL"; break;
			default:
			case self::ERR_ERROR: return "ERROR"; break;
		}
	}
	

	/** 
	 * Send notification to email and store message in file. 
	 */
	static public function storeMessage($type, $message, $url) {
		$message = strip_tags($message);
		$errorType = self::getErrorTypeString($type);
		$actualDatetime = date('Y-m-d H:i:s');
		$csvLine = 'Date/Time: ' . $actualDatetime . "\n" 
				 . 'Error type: ' . $errorType . "\n\n" 
				 . 'Message: ' . "\n" . $message . "\n\n" 
				 . 'Url: ' . $url . "\n---\n\n";
		
		// if old enough or tmp file size is too large
		if (file_exists(self::$logFile))
			$tmpFileContent = @file_get_contents(self::$logFile);
		else
			$tmpFileContent = '';
		if ((@filemtime(self::$logFileTmp) < strtotime('-' . self::$config['emails_notify_limit_minutes'] . ' MINUTES') )
			|| ((strlen($tmpFileContent) / 1024) > self::$config['emails_notify_limit_kb'])) {
			$messageBody = $tmpFileContent . $csvLine;
			file_put_contents(self::$logFileTmp, '');
			if (self::$config['sent_error_to_emails']) {
				self::sendMessage($messageBody);
			}
		} else {
			self::appendToFile(self::$logFileTmp, $csvLine);
		}
	
		// log to file
		$logFilename = self::$logFile;
		self::appendToFile($logFilename, $csvLine);
	}
	
	
	/** 
	 * Send notification to email and store message in file. 
	 */
	static public function sendMessage($message) {
		require_once(getcwd() . '/app/libs/PHPMailer/class.phpmailer.php');
		$body = "System: WildBlog\n\nURL: " . self::$config['base_url'] . "\n\nError(s): \n$message\n\n";
		$body .= "Notificated: " . self::$config['emails_to_notify'] . "\n\n";
		$body .= "--\nGenerated by WildBlog, do not reply.\n";
		
		$emails = explode(',', self::$config['emails_to_notify']);
		
		/* we want to use exceptions, thus true */
		$mail = new PHPMailer(true);
		$mail->From = 'noreply@' . str_replace('www.', '', $_SERVER['SERVER_NAME']);
		$mail->FromName = 'WildBlog Error Reporter';
		$mail->Body = $body;
		$mail->Subject = 'Error report from ' . $_SERVER['SERVER_NAME'];
		$mail->CharSet = "UTF-8";
		if (count($emails)) {
			foreach ($emails as $email) {
				$mail->AddAddress($email);
			}
			try {
				$mail->Send();
			} catch (Exception $e) {
				// pass
			}
		}
	}
	
	
	/**
	 * Write $csvLine in the end of $logFilename. If file not created, create it
	 * and change the rights 0666.
	 * @param <string> $logFilename filename of the log file
	 * @param <string> $csvLine string to write to the file
	 */
	static public function appendToFile($logFilename, $csvLine) {
		// create new file if needed
		if (!file_exists($logFilename) && is_writable(dirname($logFilename))) {
			$fp = fopen($logFilename, 'w'); 
			if ($fp) {
				fclose($fp);
				chmod($logFilename, 0666); 	// to be delete-able through FTP
			}
		}
			
		// write to file
		if (file_exists($logFilename) && is_writable($logFilename)) {
			$fp = fopen($logFilename, 'a'); 
			if ($fp) {
				fwrite($fp, $csvLine);
				fclose($fp);
			}
		}
	
	}
	
	
	/**
	 * Error handler, that is called by PHP core to handle any error.
	 * Notice, Strict and Warning types of errors will be stored as warning,
	 * other (real errors) will be stored (and handled) as errors.
	 * @param <int> $errno error number
	 * @param <string> $errstr error description
	 * @param <string> $errfile filename where error occured
	 * @param <int> $errline line where error occured
	 * @return bool false allways to continue if possible
	 */
	static public function wwErrorHandler($errno, $errstr, $errfile, $errline) {
		$message = "In file $errfile [line: $errline]: $errstr (error no. $errno)";
		if (($errno & E_WARNING || $errno & E_NOTICE)
			&& ((strpos($errfile, 'templates_c') !== false
				&& (strpos($errstr, 'Undefined index') !== false 
					|| strpos($errstr, 'Trying to get property of non-object') !== false))
			|| (strpos($errfile, 'sysplugins') !== false
				&& (strpos($errstr, 'stat') !== false
					|| strpos($errstr, 'unlink') !== false))
			)) {
			// do not log undefined variables in tempaltes
		}
		elseif ($errno & (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING)) {
			self::log(self::ERR_ERROR, $message);
		} else {
			self::log(self::ERR_WARNING, $message);
		}
		return false;
	}
	
	
	/** 
	 * Catching fatal errors:
	 * Special JS text can be print after error messages. 
	 * This JS is AJAX that sends request to ErrrorLogger.
	 * The function self::getErrorHandlerAjax() is the AJAX code.
	 */
	static public function getErrorHandlerAjax() {
		$thisUrl = ((strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) == 'https') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return "
		<script type=\"text/javascript\">
				function send_notification() {
					var request = '__system_fatal_error=' + document.getElementById('phperror').innerHTML;
					request += '&__system_fatal_url=" . urlencode($thisUrl) . "';
					if (window.XMLHttpRequest) {
						http_request = new XMLHttpRequest();
					} else if (window.ActiveXObject) {
						try {
							http_request = new ActiveXObject('Msxml2.XMLHTTP');
						} catch (eror) {
							http_request = new ActiveXObject('Microsoft.XMLHTTP');
						}
					}
				
					http_request.onreadystatechange = function() { handle_request(http_request); };
					http_request.open('POST', '$thisUrl', true);
					http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					http_request.send(request);
					//alert('sent');
				}
				
				function handle_request(http_request) {
					if (http_request.readyState == 4) {
						if (http_request.status == 200) {
							// request handling
							//alert(200);
						} else {
							// error handling
							//alert(500);
						}
					}
				}
				send_notification();
		</script>
		";	
	}
	
	
	/**
	 * HTML header of the error page.
	 * @return <string> part of HTML page - begin
	 */
	static public function getHTMLbegin($integrateAjax=false) {
		$text = self::$config['homepage_text'];
		$text = str_replace('[homepage]', '<a href="' . self::$config['base_url'] . '">homepage</a>', $text);
		$text = str_replace('[emails]', self::$config['emails_to_notify'], $text);
		return 
			"<html>\n"
			."<head>\n"
			."<title>" . self::$config['headline'] . "</title>\n"
			."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
			."<style>\n"
			."body { \n"
			."  background: " . self::$config['error_background_color'] . " url('" . self::$config['base_url'] . self::$config['error_image_path'] . "') center 100px no-repeat; \n"
			."  text-align: center;\n"
			."  " . self::$config['error_font_style'] . "\n"
			."  padding: 40px 20px;\n"
			."}\n"
			."</style>\n"
			."<base href=\"" . self::$config['base_url'] . "\" />\n"
			."</head>\n"
			."<body>\n"
			."<h1>" . self::$config['headline'] . "</h1>\n"
			."<p>" . $text . "</p>\n"
			.($integrateAjax ? "<div id=\"phperror\" style=\"display:none;\">" : '');
	}
	

	/**
	 * HTML footer of the error page.
	 * @return <string> part of HTML page - end
	 */
	static public function getHTMLend($integrateAjax=false) {
		return ($integrateAjax ? ("</div>\n" . self::getErrorHandlerAjax()) : '') . "</body>"; 
	}

	
	/**
	 * Handles HTTP request posted from error page with error string.
	 * It stores the error specification in the DB.
	 */
	static public function handleAJAX() {
		if (isset($_POST['__system_fatal_error']) && isset($_POST['__system_fatal_url'])) {
			self::loadConfig();
			self::storeMessage(
				self::ERR_FATAL_ERROR, 
				$_POST['__system_fatal_error'], 
				$_POST['__system_fatal_url']);
		}
		
	}
	
}

/**
 * Same as ErrorLogger::wwErrorHandler()
 */
function wwErrorHandler($errno, $errstr, $errfile, $errline) {
	ErrorLogger::wwErrorHandler($errno, $errstr, $errfile, $errline);
}
	

?>
