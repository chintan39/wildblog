<?php

/**
 * Update script base
 */
 
error_reporting(E_ALL);

require_once('../config/default.inc');

define('RESULT_FAIL', '(FAIL)');
define('RESULT_OK', '(OK)');
define('DIR_APP_BASE', '../../');

header('Content-Type: text/plain');

if (!(isset($_GET['__project__']))) {
	die(RESULT_FAIL . " You have to specify ?__project__=projectpath into url");
}

?>
