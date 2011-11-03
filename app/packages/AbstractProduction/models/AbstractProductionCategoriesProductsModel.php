<?php

class AbstractProductionCategoriesProductsModel extends AbstractDefaultModel {
	
	var $package = 'AbstractProduction';
	var $icon = '', $table = 'categories_products';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('category')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('product')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation($this->package . 'CategoriesModel', 'category', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation($this->package . 'ProductsModel', 'product', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>