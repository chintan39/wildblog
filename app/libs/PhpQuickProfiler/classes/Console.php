<?php

/* - - - - - - - - - - - - - - - - - - - - -

 Title : PHP Quick Profiler Console Class
 Author : Created by Ryan Campbell
 URL : http://particletree.com/features/php-quick-profiler/

 Last Updated : April 22, 2009

 Description : This class serves as a wrapper around a global
 php variable, debugger_logs, that we have created.

- - - - - - - - - - - - - - - - - - - - - */

class Console {
	
	/*-----------------------------------
	     LOG A VARIABLE TO CONSOLE
	------------------------------------*/
	
	public static function log($data) {
		$logItem = array(
			"data" => $data,
			"type" => 'log'
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		if (!isset($GLOBALS['debugger_logs']['logCount'])) {
			$GLOBALS['debugger_logs']['logCount'] = 0;
		}
		$GLOBALS['debugger_logs']['logCount'] += 1;
	}
	
	/*---------------------------------------------------
	     LOG MEMORY USAGE OF VARIABLE OR ENTIRE SCRIPT
	-----------------------------------------------------*/
	
	public static function logMemory($object = false, $name = 'PHP') {
		$memory = memory_get_usage();
		if($object) $memory = strlen(serialize($object));
		$logItem = array(
			"data" => $memory,
			"type" => 'memory',
			"name" => $name,
			"dataType" => gettype($object)
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		if (!isset($GLOBALS['debugger_logs']['memoryCount'])) {
			$GLOBALS['debugger_logs']['memoryCount'] = 0;
		}
		$GLOBALS['debugger_logs']['memoryCount'] += 1;
	}
	
	/*-----------------------------------
	     LOG A PHP EXCEPTION OBJECT
	------------------------------------*/
	
	public static function logError($exception, $message) {
		$logItem = array(
			"data" => $message,
			"type" => 'error',
			"file" => $exception->getFile(),
			"line" => $exception->getLine()
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		if (!isset($GLOBALS['debugger_logs']['errorCount'])) {
			$GLOBALS['debugger_logs']['errorCount'] = 0;
		}
		$GLOBALS['debugger_logs']['errorCount'] += 1;
	}
	
	/*------------------------------------
	     POINT IN TIME SPEED SNAPSHOT
	-------------------------------------*/
	
	public static function logSpeed($name = 'Point in Time') {
		$logItem = array(
			"data" => PhpQuickProfiler::getMicroTime(),
			"type" => 'speed',
			"name" => $name
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		if (!isset($GLOBALS['debugger_logs']['speedCount'])) {
			$GLOBALS['debugger_logs']['speedCount'] = 0;
		}
		$GLOBALS['debugger_logs']['speedCount'] += 1;
	}
	
	/*------------------------------------
	     LOG SQL QUERIES
	-------------------------------------*/

	public static function logQuery($query = 'SQL Query') {
		$logItem = array(
			"data" => $query,
			"type" => 'query',
			"name" => 'SQL query'
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		if (!isset($GLOBALS['debugger_logs']['queryCount'])) {
			$GLOBALS['debugger_logs']['queryCount'] = 0;
		}
		$GLOBALS['debugger_logs']['queryCount'] += 1;
	}
	
	/*-----------------------------------
	     SET DEFAULTS & RETURN LOGS
	------------------------------------*/
	
	public static function getLogs() {
		if(!isset($GLOBALS['debugger_logs']['memoryCount'])) $GLOBALS['debugger_logs']['memoryCount'] = 0;
		if(!isset($GLOBALS['debugger_logs']['logCount'])) $GLOBALS['debugger_logs']['logCount'] = 0;
		if(!isset($GLOBALS['debugger_logs']['speedCount'])) $GLOBALS['debugger_logs']['speedCount'] = 0;
		if(!isset($GLOBALS['debugger_logs']['queryCount'])) $GLOBALS['debugger_logs']['queryCount'] = 0;
		if(!isset($GLOBALS['debugger_logs']['errorCount'])) $GLOBALS['debugger_logs']['errorCount'] = 0;
		return $GLOBALS['debugger_logs'];
	}
}

?>