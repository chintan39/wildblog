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


class AbstractPropertiesModel extends AbstractDefaultModel {

	const VALUE_NUMBER = 1;
	const VALUE_STRING = 2;
	const VALUE_DATETIME = 3;
	
	const KIND_DESCRIPTION = 1;
	const KIND_SPECIFICATION = 2;
	
	var $package = 'Abstract';
	var $icon = 'property', $table = '_properties';
	var $properties = array();

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		AbstractAttributesModel::stdPropertyValueName();
		AbstractAttributesModel::stdPropertyValueType();
		AbstractAttributesModel::stdPropertyValueNumber();
		AbstractAttributesModel::stdPropertyValueString();
		AbstractAttributesModel::stdPropertyValueDateTime();
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	}

	public function getPossibleProperties() {
		return $this->properties;
	}

	/**
	 * Gets properties from DB according the Item specified and uses column $relationColumn for make a relation. 
	 * @param int $itemId Item id, which we search properties for
	 * @param string $relationColumn
	 * @param array of string Properties which we want to get
	 * @return array of object
	 */
	public function getPropertiesItem($item, $onlyProperties=false) {
		$relationColumn = $this->relations[get_class($item)]->sourceProperty;
		$filters = array($relationColumn . ' = ?');
		$values = array($item->id);
		if ($onlyProperties) {
			$nameValues = array();
			$nameFilters = array();
			foreach ($this->properties as $prop) {
				if (in_array($prop['name'], $onlyProperties)) {
					$nameValues[] = $prop['name'];
					$nameFilters[] = '?';
				}
			}
			if (!$nameValues) {
				return array();
			}
			$filters[] = array('name in (' . implode(', ', $nameFilters) . ')');
			$values = array_merge($values, $nameValues);
		}
		return $this->Find(get_class($this), $filters, $values);
	}

	public function deleteAll($item) {
		$filters = array($this->relations[get_class($item)]->sourceProperty . ' = ?');
		$values = array($item->id);
		$props = $this->Find(get_class($this), $filters, $values);
		if ($props) {
			foreach ($props as $p) {
				$p->DeleteYourself();
			}
		}
	}
	
} 

?>