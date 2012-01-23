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
 * Update script for version 0.4.5
 * This script rename [Database] to [ProductionDatabase] and [Logger] 
 * to [TestDatabase] in db config file
 */

require_once('updateBase.php');

function updateDbConfig() {
	$content = file_get_contents(DIR_APP_BASE . DB_CONFIG_FILE);
	$projConfigfile = DIR_APP_BASE . DIR_PROJECT_CONFIG . 'config.php';
	$contentConf = file_get_contents($projConfigfile);
	
	if (!$content) 
		return RESULT_FAIL . "Couldn't read from " . DIR_APP_BASE . DB_CONFIG_FILE;
	
	if (!$contentConf) 
		return RESULT_FAIL . "Couldn't read from " . $projConfigfile;
	
	if (strpos($content, 'TestDatabase') !== false) 
		return RESULT_FAIL . "Script seems to be already applied.";
	
	if (preg_match('/^\/\/Config::Set\([\'"]DB_PREFIX/', $contentConf)) 
		return RESULT_FAIL . "Script seems to be already applied.";
	
	$backupFilePath = DIR_APP_BASE . DB_CONFIG_FILE . '.backup.' . date('ymd.His');
	$backupFilePathConf = $projConfigfile . '.backup.' . date('ymd.His');
	
	$res = file_put_contents($backupFilePath, $content);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to $backupFilePath";
	
	$res = file_put_contents($backupFilePathConf, $contentConf);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to $backupFilePathConf";

	if (!preg_match('/\nConfig::Set\([\'"]DB_PREFIX[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/', $contentConf, $match))
		return RESULT_FAIL . "DB_PREFIX couldn't be found in " . $projConfigfile;
	
	$dbPrefix = $match[1];
	
	if (!($contentConf = preg_replace('/^Config::Set\([\'"]DB_PREFIX[\'"]/', '//${0}', $contentConf)))
		return RESULT_FAIL . "DB_PREFIX couldn't be commented in " . $projConfigfile;
	
	$content = preg_replace('/(\n\s*)(\[Database\])/', '${1}[ProductionDatabase]', $content, -1, $count);
	
	if ($count === 0) 
		return RESULT_FAIL . "Renaming Database -> ProductionDatabase";
	
	$content = preg_replace('/(\n\s*)(\[Logger\])/', '${1}[TestDatabase]', $content, -1, $count);

	if ($count === 0) 
		return RESULT_FAIL . "Logger Database -> TestDatabase";
	
	$content = preg_replace('/\n(\s*)database(\s*=\s*)[^\n]+\n/', '${0}${1}tablesprefix${2}' . $dbPrefix . "\n", $content, -1, $count);

	if ($count === 0) 
		return RESULT_FAIL . "Adding tables prefix";
	
	$res = file_put_contents(DIR_APP_BASE . DB_CONFIG_FILE, $content);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to " . DIR_APP_BASE . DB_CONFIG_FILE;
	
	$res = file_put_contents($projConfigfile, $contentConf);
	
	if ($res === false) 
		return RESULT_FAIL . "Couldn't write to " . $projConfigfile;
	
	return RESULT_OK;
}

echo "updateDbConfig: " . updateDbConfig() . "\n";

?>
