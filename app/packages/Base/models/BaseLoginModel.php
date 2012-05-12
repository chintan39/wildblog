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


class BaseLoginModel extends AbstractVirtualModel {

	var $package = 'Base';
	var $user = false;
	
    protected function attributesDefinition() {
    	$this->addMetaData(AtributesFactory::stdId());
    	$this->addMetaData(AtributesFactory::stdLoginEmail());
    	$this->addMetaData(AtributesFactory::stdLoginPassword());
	}
	
	/**
	 * Save data to some object, can be overwritten, but not has to be.
	 */
	public function Save() {
		Permission::setUser($this->user->id);
	}
	
	
	/**
	 * Checking of the values sent by form, this overwrittes standard.
	 */
	protected function checkAllFieldsValue(&$newData, &$preddefinedData, $formStep) {
		$usersMatch = Environment::getPackage('Base')->getController('Users')->tryLogin($newData['email'], $newData['password']);
		if (count($usersMatch)) {
			$this->user = $usersMatch[0];
		}
		if (!$this->user) {
			$this->addMessageSimple('errors', tg('Password of username is not correct.'));
		}
	}
}

?>
