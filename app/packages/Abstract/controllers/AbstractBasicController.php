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


class AbstractBasicController {
	
	var $model;
	var $package;
	var $description = '';
	public $order = 5;				// order of the controller (0-10)
	
	/**
	 * Constructor
	 * Bind controller with the Data model
	 * Set the template engine reference to the attribute to confortable accesss
	 * @param string $model Data model name
	 * @param string $package Package name
	 */
	public function __construct($model, $package) {
		
		$this->name = preg_replace('/^' . $package . '(.*)Controller$/', '$1', get_class($this));
		$this->description = $this->name . ' description';
		$this->package = $package;
		if (class_exists($model)) {
			$this->model = $model;
		}
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getPackage() {
		return $this->package;
	}
	
	public function getPackageObject() {
		return Environment::getPackage($this->package);
	}
	
	public function assign($variable, $value) {
		Environment::$smarty->assign($variable, $value);
	}
	

	/**
	 * Change items in the admin menu.
	 */
	public function getLinksAdminMenuLeft() {
		return array();
	}
	
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		return array();
	}
}

?>