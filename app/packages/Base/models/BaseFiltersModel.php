<?php

/**
 * Model for filters definition.
 * Attributes are added by public method addMetaData
 */
class BaseFiltersModel extends AbstractVirtualModel {

	var $package = 'Base';
	var $qualifications;
	
	/**
	 * Computes qualifications
	 */
	public function Save() {
		$this->qualifications = array();
		foreach ($this->values as $field => $value) {
			if ($value !== '') {
				if (!isset($this->qualifications['filters'])) {
					$this->qualifications['filters'] = array();
				}
				$condition = str_replace('__field__', $field, $this->getMetaData($field)->getValueConditionPattern());
				$valueAdjusted = str_replace('__value__', $value, $this->getMetaData($field)->getValueAdjustPattern());
				$this->qualifications['filters'][$condition] = array($valueAdjusted);
			}
		}
	}
	
	/**
	 * Returns computed qualifications
	 * @return array qualifications
	 */
	public function getQualifications() {
		return $this->qualifications;
	}
	
	public function getValue($fieldName) {
		if (!isset($this->values[$fieldName])) {
			return '';
		}
		return $this->values[$fieldName];
	}
	
	/*
	/**
	 * Checking of the values sent by form, this overwrittes standard.
	 * /
	public function checkAllFieldsValue(&$newData) {
		$usersMatch = Environment::getPackage("Base")->getController("Users")->tryLogin($newData["email"], $newData["password"]);
		if (count($usersMatch)) {
			$this->user = $usersMatch[0];
		}
		if (!$this->user) {
			$this->addMessageSimple("errors", "Your password or username is not correct.");
		}
	}
	*/
}

?>
