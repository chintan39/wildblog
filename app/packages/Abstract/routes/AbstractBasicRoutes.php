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


class AbstractBasicRoutes {
	
	var $model;
	var $package;
	var $abstract=false;
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
		
		$this->name = preg_replace('/^' . $package . '(.*)Routes$/', '$1', get_class($this));
		$this->description = $this->name . ' description';
		$this->package = $package;
		$this->model = $model;
	}
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and controller actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		AbstractAdminRoutes::setRouter($this);
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
	

	/**
	 * This method checks if any data are avaible in the DB, that are corresponding 
	 * to the specified data filters and values.
	 * @param array $filters SQL restrictions in the parameter format
	 * @param array $values Values, that are set in the parameters in $filter
	 * @return Returns 0 if no data are avaible, else positive count of items, that 
	 *         are corresponding.
	 */
	public function checkRequestCondition($filters, $values) {
		$data = new $this->model();
		return $data->Find($this->model, $filters, $values);
	}
}

?>