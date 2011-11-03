<?php

class AbstractProductionPropertiesModel extends AbstractPropertiesModel {

	var $package = 'AbstractProduction';
	var $icon = 'product', $table = 'properties';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(ModelMetaItem::create('product')
			->setLabel('Product')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
		
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('CommodityProductsModel', 'product', 'id'); // define a 1:many relation to category 
	}
	
	protected function propertiesDefinition() {

		$this->loadPropertiesDefinition($this->package . 'PropertiesDefinitionModel', $this->package . 'PropertiesOptionsModel');
		
	}

} 

?>