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


class NewsletterContactsModel extends AbstractSimpleModel {
	
	var $package = 'Newsletter';
	var $icon = 'user', $table = 'contacts';
	    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdAccountEmail()
    		->setDescription('e-mail to send news to')
    		->addRestrictions(Restriction::R_NOT_EMPTY | Restriction::R_UNIQUE));
    	
    	$this->addMetaData(AtributesFactory::stdFirstname());
    	
    	$this->addMetaData(AtributesFactory::stdSurname());
    	
		$this->addMetaData(AtributesFactory::stdAgreement());
		
		$this->addMetaData(AtributesFactory::stdToken()
			->setDescription('your unique character string, you can find it in your last e-mail'));

		$this->addMetaData(AtributesFactory::create('contactGroupsConnection')
			->setLabel('Groups')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setIsVisible(ModelMetaItem::NEVER));
		
		/*
		$this->addMetaData(AtributesFactory::create('messageContactsConnection')
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