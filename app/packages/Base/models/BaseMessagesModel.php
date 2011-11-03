<?php

/**
 * 
 */
 
class BaseMessagesModel extends AbstractDefaultModel {
	
	var $package = 'Base';
	var $icon = 'comments';
	var $table = 'messages';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('sender')
			->setLabel('Sender')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('recipient')
			->setLabel('Recipient')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('segment')
			->setLabel('Segment')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('whenread')
			->setLabel('When read')
			->setDescription('when the item was read')
			->setRestrictions(Restriction::R_TIMESTAMP | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('')
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType("timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'")
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('storing')
			->setLabel('Storing')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('INT(11) NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('type')
			->setLabel('Type')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('INT(11) NOT NULL'));
		
		$this->addMetaData(AbstractAttributesModel::stdText()->setIsEditable(ModelMetaItem::NEVER));
    }
    
    
}


?>
