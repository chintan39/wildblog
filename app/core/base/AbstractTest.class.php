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


class AbstractTest {
	
	var $package;
	var $name;
	var $description;
	
	public function __construct() {
		$this->name = get_class($this);
		//$this->name = preg_replace('/^' . $package . '(.*)Test$/', '$1', get_class($this));
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function run() {}
}

?>