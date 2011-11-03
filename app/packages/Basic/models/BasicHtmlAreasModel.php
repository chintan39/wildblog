<?php

class BasicHtmlAreasModel extends AbstractPagesModel {
	
	var $package = 'Basic';
	var $icon = 'html_area', $table = 'html_areas';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->getMetaData('text')->setType(Form::FORM_TEXTAREA);

    }
} 

?>