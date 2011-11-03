<?php

class NewsletterMessagesContactsModel extends AbstractDefaultModel {
	
	var $package = 'Newsletter';
	var $icon = '', $table = 'messages_contacts';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('message')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('contact')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('sent_time')
			->setLabel('Sent time')
			->setDescription('when the item was sent')
			->setRestrictions(Restriction::R_TIMESTAMP)
			->setType(Form::FORM_INPUT_DATETIME)
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('email_log')
			->setLabel('E-mail log')
			->setDescription('item in e-mail log with details obout sending')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('NewsletterMessagesModel', 'message', 'id');
        $this->addCustomRelation('NewsletterContactsModel', 'contact', 'id');
    }


} 

?>
