<?php

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
