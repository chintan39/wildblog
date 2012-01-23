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
