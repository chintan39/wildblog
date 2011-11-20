<?php

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
		global $__wwClassesLoaded;
		self::log("Total classes autoloaded: $__wwClassesLoaded");
		Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'PhpQuickProfiler/css/pQp.css');
		return self::$profiler->getDisplay();
	}
}

?>
