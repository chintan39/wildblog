<?php

class BaseEmailLogModel extends AbstractDefaultModel {
	
	var $package='Base';
	var $icon='email_log', $table='email_log';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('from')
			->setLabel('From')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
    	
		$this->addMetaData(ModelMetaItem::create('to')
			->setLabel('To')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(ModelMetaItem::create('cc')
			->setLabel('Cc')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(ModelMetaItem::create('bcc')
			->setLabel('Bcc')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL'));
    	
    	
		$this->addMetaData(ModelMetaItem::create('reply')
			->setLabel('Reply')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(ModelMetaItem::create('subject')
			->setLabel('Subject')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
    	
		$this->addMetaData(ModelMetaItem::create('text')
			->setLabel('Text')
			->setType(Form::FORM_TEXTAREA)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('TEXT NOT NULL'));
    	
		$this->addMetaData(ModelMetaItem::create('alt_text')
			->setLabel('Alternative text')
			->setType(Form::FORM_TEXTAREA)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('TEXT NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('send_time')
			->setLabel('Send time')
			->setRestrictions(Restriction::R_TIMESTAMP | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_DATETIME)
			->setIsEditable(ModelMetaItem::NEVER)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP')
			->setSqlIndex('index')
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ON_NEW));

		$this->addMetaData(ModelMetaItem::create('send_result')
			->setLabel('Send Result')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setSqlIndex('index'));
		
		$this->addMetaData(ModelMetaItem::create('send_error')
			->setLabel('Send Error')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
    }
} 

?>