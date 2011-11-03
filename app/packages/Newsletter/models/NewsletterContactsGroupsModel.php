<?php

class NewsletterContactsGroupsModel extends AbstractDefaultModel {
	
	var $package = 'Newsletter';
	var $icon = '', $table = 'contacts_groups';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('contact')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('group')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('NewsletterContactsModel', 'contact', 'id'); // define a many:many relation to Group through NewsletterGroup
        $this->addCustomRelation('NewsletterGroupsModel', 'group', 'id'); // define a many:many relation to Group through NewsletterGroup
    }


} 

?>