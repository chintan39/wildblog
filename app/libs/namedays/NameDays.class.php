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

class NameDays {

	static $loaded=false;
	static $namedays = array();
	static $bankholidays = array();
	static $flowerevents = array();

	function __construct($lang) {
	
	} 
	
	static public function init($lang='cs') {
		if (!self::$loaded) {
			self::setLanguage($lang);
			self::$loaded = true;
		}
	}
	
	static public function setLanguage($lang) {
		$file = $lang.'.php';
		if (!file_exists($file)) {
			$file = 'cs.php';
			//throw new Exception("File '$file' for lang '$lang' doesn't exist.");
		}
		include($file);
		self::$namedays = $namedays;
		self::$bankholidays = $bankholidays;
		self::$flowerevents = $flowerevents;
	}
	
	static private function getKey($month=null, $day=null) {
		if (!self::$loaded)
			throw new Exception('Modul NameDays not loaded.');
		if ($month === null && $day === null) {
			$month = (int)date('m');
			$day = (int)date('d');
		}
		return "$month-$day";
	}

	static public function getName($month=null, $day=null) {
		if (!self::$loaded)
			throw new Exception('Modul NameDays not loaded.');
		$key = self::getKey($month, $day);
		if (!array_key_exists($key, self::$namedays))
			throw new Exception("Key '$key' doesn't exist in names.");
		return self::$namedays[$key];
	}

	static public function getBankHoliday($month=null, $day=null) {
		if (!self::$loaded)
			throw new Exception('Modul NameDays not loaded.');
		$key = self::getKey($month, $day);
		if (!array_key_exists($key, self::$bankholidays))
			return '';
		return self::$bankholidays[$key];
	}
	
	static public function getToleranceFlowerDays($tolerance=10, $year=null, $month=null, $day=null) {
		if (!self::$loaded)
			throw new Exception('Modul NameDays not loaded.');

		if ($tolerance < 0)
			return array();

		if ($year === null) 
			$year = date('Y');

		$timeset = true;

		if ($month === null && $day === null) {
			$month = (int)date('m');
			$day = (int)date('d');
			$timeset = false;
		}

		$result = array();

		for ($i=0; $i <= $tolerance; $i++) {
			if ($timeset) {
				$datetime = date_create("$year-$month-$day");
				date_add($datetime, date_interval_create_from_date_string("$tolerance days"));
				$date = date_format($datetime, 'Y-m-d');
				$m = (int)date_format($datetime, 'm');
				$d = (int)date_format($datetime, 'd');
			} else {
				$datetime = strtotime("+$i days");
				$date = date('Y-m-d', $datetime);
				$m = (int)date('m', $datetime);
				$d = (int)date('d', $datetime);
			}
			// TODO: add how many days to the event
			$item = new stdClass();
			$item->days = $i;
			if (array_key_exists("$m-$d", self::$flowerevents))
				$item->name = self::$flowerevents["$m-$d"];
			if (array_key_exists("$date", self::$flowerevents))
				$item->name = self::$flowerevents["$date"];
			if (isset($item->name))
				$result[] = $item;
		}
		return $result;
	}

}

?>
