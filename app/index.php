<?php
		setlocale(LC_ALL, 'cs_CZ.utf8');

require_once('app/config/default.inc');

if (!file_exists(DIR_PROJECT_CONFIG . 'config.php')) {
	require DIR_APP . 'install.php';
	exit;
}
require_once(DIR_CORE_BASE . 'Config.class.php');
require_once(DIR_CONFIG . 'project_default.inc');
require_once(DIR_PROJECT_CONFIG . 'config.php');

require_once(DIR_CORE_BASE . 'ErrorLogger.class.php');
ErrorLogger::handleAJAX();
ErrorLogger::init();

try {
	require_once(DIR_CORE_BASE . 'Environment.class.php');
	require_once(DIR_CORE_ROUTING . 'Request.class.php');

	// don't use this devil's tool 
	ini_set('register_globals', 0);

	// set session's cookie lifetime to 30min
	ini_set('session.cookie_lifetime', 30*60);

	// for debuging all errors will be printed
	if (Config::Get('DEBUG_MODE')) {
		error_reporting(E_ALL);
	} else { 
		error_reporting(0);
	}	
	
	// we will use sessions by default
	session_set_cookie_params(0);
	session_start();
	
	// initialize the global environment object
	Environment::init();
	
	ErrorLogger::init(!Config::Get('DEBUG_MODE'));
	
} catch (Exception $e) {
	ErrorLogger::handleException($e);
}

try {
	// handle the request using the Request object
	Request::handleRequest();

} catch (Exception $e) {
	if (Config::Get('DEBUG_MODE')) {
		throw new Exception($e);
	} else {
		ErrorLogger::handleException($e);
	}
}

?>
