<?php

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