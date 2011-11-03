<?php

class AbstractProductionPropertiesDefinitionModel extends AbstractPropertiesDefinitionModel {

	var $package = 'AbstractProduction';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$kindOptions = array(
			array('id' => AbstractPropertiesModel::KIND_DESCRIPTION, 'value' => 'Describes product'),
			array('id' => AbstractPropertiesModel::KIND_SPECIFICATION, 'value' => 'Specifies product'),
			);
		
		$this->addMetaData(ModelMetaItem::create('prop_kind')
			->setLabel('Kind')
			->setType(Form::FORM_SELECT)
			->setOptions($kindOptions)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\''));
		
		$this->addMetaData(ModelMetaItem::create('groupDefinitionConnection')
			->setLabel('Groups')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany($this->package . 'PropertiesGroupsModel', $this->package . 'PropertiesGroupsDefinitionModel', 'property', 'group', 'groupDefinitionConnection'); // define a many:many relation to Tag through BlogTag
    }
  

}

?>
