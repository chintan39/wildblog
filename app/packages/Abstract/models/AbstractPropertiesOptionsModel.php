<?php

class AbstractPropertiesOptionsModel extends AbstractCodebookModel {

	var $package = 'Abstract';
	var $icon = 'codebook';
	var $table = 'properties_options';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('property')
			->setLabel('Property')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation($this->package . 'PropertiesDefinitionModel', 'property', 'id'); // define a 1:many relation to vat 
	}
}

?>
