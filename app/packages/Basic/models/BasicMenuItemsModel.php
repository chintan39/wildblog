<?php

class BasicMenuItemsModel extends AbstractStructuredCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'page', $table = 'menu_items';
	var $languageSupportAllowed = true;
	var $activity;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AbstractAttributesModel::stdLink());
		
		$this->addMetaData(ModelMetaItem::create('menu')
			->setLabel('Menu')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('BasicMenuModel', 'menu', 'id'); // define a 1:many relation to Reaction 
    }


} 

?>