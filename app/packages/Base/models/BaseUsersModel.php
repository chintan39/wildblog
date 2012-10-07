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
 * 
 */
 
require_once(DIR_PACKAGES . 'Abstract' . DIRECTORY_SEPARATOR . DIR_MODELS . 'AbstractSimpleModel.php');

class BaseUsersModel extends AbstractSimpleModel {
	
	var $package='Base';
	var $icon='user', $table='users';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$this->addMetaData(AtributesFactory::stdAccountEmail()
			->addRestrictions(Restriction::R_NO_EDIT_ON_EMPTY));
    	$this->addMetaData(AtributesFactory::stdFirstname());
    	$this->addMetaData(AtributesFactory::stdSurname());
    	$this->addMetaData(AtributesFactory::stdLoginPassword()
    			->setName('old_password')
    			->setLabel('Old password')
    			->setForceIsInDb(false)
    			->setOptionsMustBeSelected(true)
    			->setUseSalt(false));
    	$this->addMetaData(AtributesFactory::stdAccountpassword()
    			->setCheckMethod('checkOldPassword')
    			->setLabel('New password'));
    	$this->addMetaData(AtributesFactory::stdAccountPermissions()
			->addRestrictions(Restriction::R_NO_EDIT_ON_EMPTY));
    	$this->addMetaData(AtributesFactory::stdLastLogged()
			->addRestrictions(Restriction::R_NO_EDIT_ON_EMPTY));
    	$this->getMetaData('active')
			->addRestrictions(Restriction::R_NO_EDIT_ON_EMPTY);

		$this->addMetaData(AtributesFactory::create('private_config')
			->setLabel('Private config')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL')
			->setFormTab(Form::TAB_PROPERTIES)
			->setExtendedTable(false)
			->setIsVisible(ModelMetaItem::NEVER)
			->addRestrictions(Restriction::R_NO_EDIT_ON_EMPTY));
	
    }

    
    /**
     * Method creates the title used in the select box.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	$o = trim($this->firstname . " " . $this->surname);
    	return $o ? $o : parent::makeSelectTitle();
    }

    public function checkOldPassword($value, &$meta, &$newData) {
		$usersMatch = Environment::getPackage('Base')->getController('Users')->tryLogin($this->email, Request::$post['old_password']);
		if (!$usersMatch || !count($usersMatch)) {
			$this->addMessageField("errors", 'old_password', tg("Old password is not set properly"));
		}
    } 

}


?>
