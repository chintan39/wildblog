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


/**
 * Model for filters definition.
 * Attributes are added by public method addMetaData
 */
class BaseFiltersModel extends AbstractVirtualModel {

	var $package = 'Base';
	private $qualifications;
	
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
				$this->qualifications['filters'][] = new ItemQualification($condition, array($valueAdjusted));
			}
		}
	}
	
	
	public function getValue($fieldName) {
		if (!isset($this->values[$fieldName])) {
			return '';
		}
		return $this->values[$fieldName];
	}
	
    public function getQualifications() {
		return $this->qualifications;
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
