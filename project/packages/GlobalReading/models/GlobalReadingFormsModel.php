<?php

class GlobalReadingFormsModel extends AbstractPagesModel {

	var $package = 'GlobalReading';
	var $icon = 'application', $table = 'forms';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('category')
			->setLabel('Category')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    	$this->getMetaData('text')
    		->setType(Form::FORM_TEXTAREA)
    		->setStyle('height: 500px;');

    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('GlobalReadingCategoriesModel', 'category', 'id'); // define a 1:many relation to Reaction 
	}

} 

?>