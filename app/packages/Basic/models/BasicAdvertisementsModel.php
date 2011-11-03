<?php

class BasicAdvertisementsModel extends AbstractPagesModel {
	
	var $package = 'Basic';
	var $icon = 'advertisements', $table = 'advertisements';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->getMetaData('text')->setType(Form::FORM_TEXTAREA);

    }
} 

?>