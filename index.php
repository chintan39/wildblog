<?php

date_default_timezone_set('Europe/Prague');

if (!file_exists('project/config/config.php')) {
	require 'app/install.php';
	exit;
}

require_once('app/core/ErrorLogger.class.php');

ErrorLogger::handleAJAX();
ErrorLogger::init();

try {
	require_once('project/config/config.php');
	require_once(DIR_CORE . 'Environment.class.php');
	require_once(DIR_CORE . 'Request.class.php');
	
	// don't use this devil's tool 
	ini_set('register_globals', 0);

	// for debuging all errors will be printed
	if (Config::Get('DEBUG_MODE')) {
		error_reporting(E_ALL);
	} else { 
		error_reporting(0);
	}	
	
	// we will use sessions by default
	session_start();
	
	// initialize the global environment object
	$env = new Environment();
	$env->init();

	ErrorLogger::init(!Config::Get('DEBUG_MODE'));
	
} catch (Exception $e) {
	ErrorLogger::handleException($e);
}

try {
	// handle the request using the Request object
	$req = new Request();
	$req->handleRequest();

} catch (Exception $e) {
	if (Config::Get('DEBUG_MODE')) {
		throw new Exception($e);
	} else {
		ErrorLogger::handleException($e);
	}
}

?>
