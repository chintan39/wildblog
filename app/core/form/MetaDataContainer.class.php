<?php

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
