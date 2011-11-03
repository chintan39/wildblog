<?php

class AbstractProductionCategoriesModel extends AbstractStructuredPagesModel {

	var $package = 'AbstractProduction';
	var $icon = 'product_category', $table = 'categories';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(ModelMetaItem::create('group')
			->setLabel('Properties Group')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
		
		$this->addMetaData(ModelMetaItem::create('categoriesProductsConnection')
			->setLabel('Products')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation($this->package . 'PropertiesGroupsModel', 'group', 'id'); // define a 1:many relation to manofacturer 
        $this->addCustomRelationMany($this->package . 'ProductsModel', $this->package . 'CategoriesProductsModel', 'category', 'product', 'categoriesProductsConnection'); // define a many:many relation to Tag through BlogTag
	}
	

} 

?>