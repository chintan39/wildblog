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


class BasicMenuModel extends AbstractCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'page', $table = 'menu';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('all_pages')
			->setLabel('All pages')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'0\'')
			->setDescription('If checked, menu will be available on all pages.'));
		
		$this->addMetaData(ModelMetaItem::create('menuItemsConnection')
			->setLabel('Menu items')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSelector(true)
			->setSelectorDisplayMode(Javascript::SELECTOR_DIPLAY_MODE_TEXTS)
			->setLinkNewItem(array('package' => $this->package, 'controller' => 'MenuItems', 'action' => 'actionSimpleNew')));
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();

	    $this->addCustomRelation('BasicMenuItemsModel', 'id', 'menu', 'menuItemsConnection'); // define a 1:many relation to Reaction 
    }
} 

?>