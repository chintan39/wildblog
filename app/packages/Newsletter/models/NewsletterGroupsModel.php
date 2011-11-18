<?php

class NewsletterGroupsModel extends AbstractCodebookModel {
	
	var $package = 'Newsletter';
	var $icon = 'tag', $table = 'groups';

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('NewsletterContactsModel', 'NewsletterContactsGroupsModel', 'group', 'contact'); // define a many:many relation to Group through NewsletterGroup
    }
    
    /**
     * The method loads contacts related to the group specified by $group parameter.
     * @param string $itemCollectionIdentifier
     * @param object $group Group model
     * @return object ItemsCollection
     */
    public function groupContacts($itemCollectionIdentifier, &$group) {
    	$contacts = $group->Find('NewsletterContactsModel', array(), array(), array(), array('id'));
    	if (count($contacts)) {
    		$values = array();
    		foreach ($contacts as $p) {
    			$values[] = $p->id;
    		}
    		$this->addQualification(' id in (?' . str_repeat(', ?', count($values)-1) . ')', $values);
    	}
    	
    	$article = new BasicArticlesModel();
    	$article->setLimit($this->getLimit());

    	return $article->getCollectionItems();
    }
    
    

} 

?>