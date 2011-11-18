<?php

class NewsletterContactsModel extends AbstractSimpleModel {
	
	var $package = 'Newsletter';
	var $icon = 'user', $table = 'contacts';
	    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdAccountEmail()
    		->setDescription('e-mail to send news to')
    		->addRestrictions(Restriction::R_NOT_EMPTY | Restriction::R_UNIQUE));
    	
    	$this->addMetaData(AbstractAttributesModel::stdFirstname());
    	
    	$this->addMetaData(AbstractAttributesModel::stdSurname());
    	
		$this->addMetaData(AbstractAttributesModel::stdAgreement());
		
		$this->addMetaData(AbstractAttributesModel::stdToken()
			->setDescription('your unique character string, you can find it in your last e-mail'));

		$this->addMetaData(ModelMetaItem::create('contactGroupsConnection')
			->setLabel('Groups')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setIsVisible(ModelMetaItem::NEVER));
		
		/*
		$this->addMetaData(ModelMetaItem::create('messageContactsConnection')
			->setLabel('Messages')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setOptionsModel('MessageContactsModel'));
		*/
    	
    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('NewsletterMessagesModel', 'NewsletterMessagesContactsModel', 'contact', 'message');
        $this->addCustomRelationMany('NewsletterGroupsModel', 'NewsletterContactsGroupsModel', 'contact', 'group', 'contactGroupsConnection', 'contactGroupsConnection'); // define a many:many relation to Group through NewsletterGroup
    }
    
    
    /**
     * Method creates the title used in the select box.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	$name = trim($this->firstname . ' ' . $this->surname);
    	$o = $this->email . ($name ? ' (' . $name . ')' : '');
    	return $o ? $o : parent::makeSelectTitle();
    }

    /**
     * Returns the list of items to make the relation to another model. 
     * So the items returned will be used by the select list.
     * @return array List of items
     */
    public function listSelect() {
    	$query = "
			SELECT id, email as value
			FROM `wildblog_newsletter_contacts` 
			WHERE email != ''
			LIMIT 1000
			";
		
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('Newsletter: listSelect SQL: ' . $query); // QUERY logger
		}
		
		$res = dbConnection::getInstance()->query($query);
    	$selectItems = array();
    	if ($res) {
    		while ($tmp = dbConnection::getInstance()->fetchRow()) {
    			$selectItems[] = $tmp;
    		}
    	}
    	return $selectItems; 
    }

} 

?>