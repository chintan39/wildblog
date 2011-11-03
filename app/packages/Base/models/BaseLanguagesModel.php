<?php

/**
 * 
 */
 
class BaseLanguagesModel extends AbstractCodebookModel {
	
	var $package = 'Base';
	var $icon = 'languages';
	var $table = 'languages';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
   		$this->addMetaData(ModelMetaItem::create('front_end')
			->setLabel('Front-end')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
    	
   		$this->addMetaData(ModelMetaItem::create('back_end')
			->setLabel('Back-end')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
 	
	
    }
    
    public function loadLanguages() {
		$cache = $this->loadCache('languages');
		if ($cache) {
			return $cache;
		}
		$languages = $this->Find('BaseLanguagesModel');
		$this->saveCache('languages', $languages, array($this->name));
    	return $languages;
    }
}


?>
