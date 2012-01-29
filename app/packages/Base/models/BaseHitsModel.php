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
 
class BaseHitsModel extends AbstractDefaultModel {
	
	var $package = 'Base';
	var $icon = 'settings';
	var $table = 'hits';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('action')
			->setLabel('Action')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('item')
			->setLabel('Item ID')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('url')
			->setLabel('Request URL')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('referer')
			->setLabel('Referer')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('generation_time')
			->setLabel('Generation time')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType("DECIMAL(12,4) NOT NULL DEFAULT '0.0'"));

		$this->addMetaData(AtributesFactory::create('memory_consumption')
			->setLabel('Memory consumption')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType("DECIMAL(12,4) NOT NULL DEFAULT '0.0'"));

		$this->addMetaData(AtributesFactory::create('lang')
			->setLabel('Language')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(10) NOT NULL'));

		$this->addMetaData(AtributesFactory::stdInserted()->setIsEditable(ModelMetaItem::NEVER));
    	$this->addMetaData(AtributesFactory::stdIP()->setIsEditable(ModelMetaItem::NEVER));
    }
    
    
}


?>
