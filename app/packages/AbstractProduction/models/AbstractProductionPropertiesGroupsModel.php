<?php

class AbstractProductionPropertiesGroupsModel extends AbstractCodebookModel {

	var $package = 'AbstractProduction';
	var $icon = 'codebook';
	var $table = 'properties_groups';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(ModelMetaItem::create('groupDefinitionConnection')
			->setLabel('Property')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany($this->package . 'PropertiesDefinitionModel', $this->package . 'PropertiesGroupsDefinitionModel', 'group', 'property', 'groupDefinitionConnection'); // define a many:many relation to Tag through BlogTag
    }
}

?>
