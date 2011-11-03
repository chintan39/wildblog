<?php

class ResearchFillingsModel extends AbstractSimpleModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'filling';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('research')
			->setLabel('Research')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('ResearchResearchesModel', 'research', 'id'); // define a 1:many relation to Research
    }
} 

?>