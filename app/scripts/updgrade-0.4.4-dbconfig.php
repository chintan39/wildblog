<?php

/**
 * Update script for version 0.4.4
 * This script adds dbconnection=pdo to db config file
 */

require_once('updateBase.php');

function updateDbConfig() {
	$content = file_get_contents(DIR_APP_BASE . DB_CONFIG_FILE);
	
	if (!$content) 
		return RESULT_FAIL . "Couldn't read from " . DIR_APP_BASE . DB_CONFIG_FILE;
	
	if (strpos($content, 'dbconnection') !== false) 
		return RESULT_FAIL . "Script seems to be already applied.";
	
	$backupFilePath = DIR_APP_BASE . DB_CONFIG_FILE . '.backup.' . date('ymd.His');
	$res = file_put_contents($backupFilePath, $content);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to $backupFilePath";
	
	$content = preg_replace('/(\n\s*)(dbtype)(\s*=\s*)mysql/', '${1}dbconnection${3}pdo${0}', $content, -1, $count);
	
	if ($count === 0) 
		return RESULT_FAIL . "Adding dbconnection = pdo";
	
	$res = file_put_contents(DIR_APP_BASE . DB_CONFIG_FILE, $content);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to " . DIR_APP_BASE . DB_CONFIG_FILE;
	
	return RESULT_OK;
}

echo "updateDbConfig: " . updateDbConfig() . "\n";

?>
