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
 * Class used to debug application. 
 * Some places in the application code can be mark as checkpoints, that checkpoints 
 * will be printed on the ond of the generated with corresponding timestamú info.
 * Benchmark can slowdown the executing, so it should be used only in debug mode.
 * @var <array> $data Benchmark data
 * @var <int> $beginning time when Benchmark started
 */
class Benchmark {
	
	static public $profiler;
	
	/**
	 * Constructor, first timestamp will be set.
	 */
	public function __construct() {
		if (Config::Get('DEBUG_MODE')) {
			self::$profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());
			Console::log('Begin logging data');
		}
	}
	
	/**
	 * Sets the checkpoint to this point.
	 */
	static public function log($string) {
		if (Config::Get('DEBUG_MODE')) {
			Console::log($string);
			Console::logMemory();
		}
	}
	

	/**
	 * Sets the checkpoint to this point.
	 */
	static public function logMemory($string, $name='') {
		if (Config::Get('DEBUG_MODE')) {
			Console::logMemory($string, $name);
			Console::logMemory();
		}
	}
	

	/**
	 * Sets the checkpoint to this point.
	 */
	static public function logSpeed($string) {
		if (Config::Get('DEBUG_MODE')) {
			Console::logSpeed($string);
		}
	}
	
	
	/**
	 * Log SQL query.
	 */
	static public function logQuery($string) {
		if (Config::Get('DEBUG_MODE')) {
			Console::logQuery($string);
		}
	}
	
	
	static public function getDisplay() {
		if (!Config::Get('DEBUG_MODE')) {
			return '';
		}
		foreach (Environment::$smarty->get_template_vars() as $k => $v) {
			self::logMemory($v, "Smarty variable '$k'");
		} 
		self::logMemory(serialize(MetaDataContainer::$data), "MetaDataContainer size estimation");
		global $__wwClassesLoaded;
		self::log("Total classes autoloaded: $__wwClassesLoaded");
		Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'PhpQuickProfiler/css/pQp.css');
		return self::$profiler->getDisplay();
	}
}

?>
