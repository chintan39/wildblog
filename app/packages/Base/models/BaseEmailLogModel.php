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


class BaseEmailLogModel extends AbstractDefaultModel {
	
	var $package='Base';
	var $icon='email_log', $table='email_log';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('from')
			->setLabel('From')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
    	
		$this->addMetaData(AtributesFactory::create('to')
			->setLabel('To')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(AtributesFactory::create('cc')
			->setLabel('Cc')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(AtributesFactory::create('bcc')
			->setLabel('Bcc')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
    	
		$this->addMetaData(AtributesFactory::create('reply')
			->setLabel('Reply')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(AtributesFactory::create('subject')
			->setLabel('Subject')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
    	
		$this->addMetaData(AtributesFactory::create('text')
			->setLabel('Text')
			->setType(Form::FORM_TEXTAREA)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(AtributesFactory::create('alt_text')
			->setLabel('Alternative text')
			->setType(Form::FORM_TEXTAREA)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('TEXT NOT NULL'));

		$this->addMetaData(AtributesFactory::create('send_time')
			->setLabel('Send time')
			->setRestrictions(Restriction::R_TIMESTAMP | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_DATETIME)
			->setIsEditable(ModelMetaItem::NEVER)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ON_NEW));

		$this->addMetaData(AtributesFactory::create('send_result')
			->setLabel('Send Result')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setSqlindex(ModelMetaIndex::INDEX));
		
		$this->addMetaData(AtributesFactory::create('send_error')
			->setLabel('Send Error')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
    }
} 

?>