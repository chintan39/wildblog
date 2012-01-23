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


class AbstractVirtualModel extends AbstractBasicModel {
	
	var $package = 'Abstract';
	var $fields = array(); // array of $fieldName => $attrName
	var $values = array();	// array of field_name => value
	
    function __construct($id = false) {
    	parent::__construct($id);
	}
	
	public function getFields() {
		return array_keys($this->getMetaData());
	}
	
	public function getValue($fieldName) {
		return $this->values[$fieldName];
	}
	
	public function getChangeAbleColumns() {
		return array_keys($this->getMetaData());
	}

	/**
	 * Overwritten standard getter.
	 */
	public function __get($property) {
		if (array_key_exists($property, $this->values)) {
			if (is_array($this->values[$property])) {
				return (array)$this->values[$property];
			} else {
				return $this->values[$property];
			}
		} else {
			if (isset($this->$property) && is_array($this->$property)) {
				return (array)$this->$property;
			} else {
				return $this->$property;
			}
		}
	}
	
	/**
	 * Overwritten standard setter.
	 */
	public function __set($property, $value) {
		if ($this->hasMetaData($property)) {
			$this->values[$property] = $value;
		} else {
			$this->$property = $value;
		}
	}
	
	public function addMetaData($metaItem) {
		parent::addMetaData($metaItem);
		$this->values[$metaItem->getName()] = $metaItem->getDefaultValue();
	}


}

?>
