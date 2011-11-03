<?php

class GlobalReadingCategoriesModel extends AbstractCodebookModel {
	
	var $package = 'GlobalReading';
	var $icon = 'tag', $table = 'categories';


    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdText()
    		->setName('example')
    		->setLabel('Example')
    		->setType(Form::FORM_TEXTAREA)
    		->setStyle('height: 500px;')
    		);
    
    }
}

?>
