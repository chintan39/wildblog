<?php

class AbstractProductionPropertiesGroupsDefinitionModel extends AbstractDefaultModel {
	
	var $package = 'AbstractProduction';
	var $icon = '', $table = 'groups_definition';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('property')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('group')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation($this->package . 'PropertiesDefinitionModel', 'property', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation($this->package . 'PropertiesGroupsModel', 'group', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>