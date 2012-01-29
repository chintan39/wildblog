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

    	$this->addMetaData(AtributesFactory::stdAccountEmail());
    	$this->addMetaData(AtributesFactory::stdFirstname());
    	$this->addMetaData(AtributesFactory::stdSurname());
    	$this->addMetaData(AtributesFactory::stdAccountpassword());
    	$this->addMetaData(AtributesFactory::stdAccountPermissions());
    	$this->addMetaData(AtributesFactory::stdLastLogged());

		$this->addMetaData(ModelMetaItem::create('private_config')
			->setLabel('Private config')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL')
			->setFormTab(Form::TAB_PROPERTIES)
			->setExtendedTable(false)
			->setIsVisible(ModelMetaItem::NEVER));
	
    }

    
    /**
     * Method creates the title used in the select box.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	$o = trim($this->firstname . " " . $this->surname);
    	return $o ? $o : parent::makeSelectTitle();
    }
}


?>
