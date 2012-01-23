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