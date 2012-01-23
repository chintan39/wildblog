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


class MetaDataContainer {
	
	static public $data = array();
	
	/**
	 * Getter for metadata.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 * @return array metadata for specific $attrName or all, if $attrName is set false
	 */
	static public function getMetaData($modelName, $attrName=false) {
		if ($attrName === false) {
			return isset(self::$data[$modelName]) ? self::$data[$modelName] : null;
		} else {
			return isset(self::$data[$modelName][$attrName]) ? self::$data[$modelName][$attrName] : null;
		}
	}
	
	/**
	 * Returns true if metadata exists.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 * @return bool Returns true if metadata exists.
	 */
	static public function hasMetaData($modelName, $attrName) {
		return isset(self::$data[$modelName][$attrName]);
	}
	
	static public function addMetaData($modelName, $metaItem) {
		if (!is_object($metaItem)) throw new Exception("F");
		self::$data[$modelName][$metaItem->getName()] = $metaItem;
	}
	
	
	static public function setMetaData($modelName, $metaItemName, $paramName, $value) {
		self::$data[$modelName][$metaItemName]->setParam($paramName, $value);
	}

		
	static public function removeMetaData($modelName, $metaName) {
		unset(self::$data[$modelName][$metaName]);
	}


	static public function getFieldOptions($modelName, $fieldName) {
		$options = array();
		if (MetaDataContainer::hasMetaData($modelName, $fieldName)) {
			$field = MetaDataContainer::getMetaData($modelName, $fieldName);
			if (in_array($field->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_SELECT_FOREIGNKEY))) {
				$listMethodName = $field->getOptionsMethod();
				$foreignModelName = $field->getOptionsModel();
				if (!class_exists($foreignModelName)) throw new Exception("Class '$foreignModelName' used by field '$fieldName' is not defined.");
				$foreignModel = new $foreignModelName();
				$options = $foreignModel->$listMethodName();
			} elseif ($field->hasOptions()) {
				$options = $field->getOptions();
			}
		}
		return $options;
	}

}

?>
