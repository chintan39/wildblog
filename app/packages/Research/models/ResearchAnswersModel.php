<?php

class ResearchAnswersModel extends AbstractDefaultModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'answers';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

		$this->addMetaData(ModelMetaItem::create('value')
			->setLabel('Value')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('varchar(255) NOT NULL')
			->setSqlIndex('fulltext')
			);
    	
		$this->addMetaData(ModelMetaItem::create('filling')
			->setLabel('Filling')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
		
		$this->addMetaData(ModelMetaItem::create('question')
			->setLabel('Question')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('ResearchFillingsModel', 'filling', 'id'); // define a 1:many relation to Research
	    $this->addCustomRelation('ResearchQuestionsModel', 'question', 'id'); // define a 1:many relation to Research
    }
} 

?>