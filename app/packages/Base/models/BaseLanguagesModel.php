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
 
class BaseLanguagesModel extends AbstractCodebookModel {
	
	var $package = 'Base';
	var $icon = 'languages';
	var $table = 'languages';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
   		$this->addMetaData(AtributesFactory::create('front_end')
			->setLabel('Front-end')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
    	
   		$this->addMetaData(AtributesFactory::create('back_end')
			->setLabel('Back-end')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
 	
		$this->setMetaData('url', 'sqlIndex', null);
		
    }
    
    public function loadLanguages() {
		$cache = $this->loadCache('languages');
		if ($cache) {
			return $cache;
		}
		$languages = $this->Find('BaseLanguagesModel');
		$this->saveCache('languages', $languages, array($this->name));
    	return $languages;
    }
}


?>
