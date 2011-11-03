<?php

/**
 * Static class Config handles default project config, as well as 
 * the config specified by user.
 */
class Config {

	static $data = array();
	public static $meta = array();
	
	const BOOL = 1;
	const INT = 2;
	const STRING = 3;

	/**
	 * Setting the $value to the $name
	 * @param <string> $name Name of the config item
	 * @param <mixed> $value Value of the config item
	 */
	public static function Set($name, $value, $label=null, $type=null, $safe=null, $inDB=null) {
		self::$data[$name] = $value;
		if (!isset(self::$meta[$name])) {
			self::$meta[$name] = new stdClass;
		}
		if (!isset(self::$meta[$name]->label) || self::$meta[$name]->label === null) {
			self::$meta[$name]->label = $label;
		}
		if (!isset(self::$meta[$name]->type) || self::$meta[$name]->type === null) {
			self::$meta[$name]->type = $type;
		}
		if (!isset(self::$meta[$name]->safe) || self::$meta[$name]->safe === null) {
			self::$meta[$name]->safe = $safe;
		}
		if (!isset(self::$meta[$name]->inDB) || self::$meta[$name]->inDB === null) {
			self::$meta[$name]->inDB = $inDB;
		}
	}
	
	/**
	 * Getting the value specified by $name
	 * @param <string> $name Name of the config item
	 * @return <mixed> Value of the config item
	 */
	public static function Get($name) {
		if (!array_key_exists($name, self::$data)) {
			throw new Exception("Config item $name not defined in Config class.");
		}
		return self::$data[$name];
	}
	
	/**
	 * Getting the value specified by $name, if not set, $default is returned
	 * @param <string> $name Name of the config item
	 * @param <mixed> $defualt default value
	 * @return <mixed> Value of the config item
	 */
	public static function GetCond($name, $default) {
		if (!array_key_exists($name, self::$data)) {
			return $default;
		}
		return self::$data[$name];
	}
	
}

?>
