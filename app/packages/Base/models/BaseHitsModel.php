<?php

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
    	
		$this->addMetaData(ModelMetaItem::create('action')
			->setLabel('Action')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('item')
			->setLabel('Item ID')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('url')
			->setLabel('Request URL')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('referer')
			->setLabel('Referer')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(255) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('generation_time')
			->setLabel('Generation time')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType("DECIMAL(12,4) NOT NULL DEFAULT '0.0'"));

		$this->addMetaData(ModelMetaItem::create('memory_consumption')
			->setLabel('Memory consumption')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType("DECIMAL(12,4) NOT NULL DEFAULT '0.0'"));

		$this->addMetaData(ModelMetaItem::create('lang')
			->setLabel('Language')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(10) NOT NULL'));

		$this->addMetaData(AbstractAttributesModel::stdInserted()->setIsEditable(ModelMetaItem::NEVER));
    	$this->addMetaData(AbstractAttributesModel::stdIP()->setIsEditable(ModelMetaItem::NEVER));
    }
    
    
}


?>
