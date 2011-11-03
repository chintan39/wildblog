<?php

class ResearchQuestionsModel extends AbstractCodebookModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'questions';
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
		
		$typeOptions = array(
			array('id' => Form::FORM_INPUT_TEXT, 'value' => 'Text'),
			array('id' => Form::FORM_INPUT_NUMBER, 'value' => 'Number'),
			array('id' => Form::FORM_SELECT_FOREIGNKEY, 'value' => 'Select from options'),
			array('id' => Form::FORM_MULTISELECT_FOREIGNKEY, 'value' => 'Multiselect from options'),
			array('id' => Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, 'value' => 'Multeselect from options interactive'),
			array('id' => Form::FORM_RADIO_FOREIGNKEY, 'value' => 'Radio'),
			);
		$this->addMetaData(ModelMetaItem::create('type')
			->setLabel('Type')
			->setType(Form::FORM_SELECT)
			->setOptions($typeOptions)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));

		$this->addMetaData(ModelMetaItem::create('required')
			->setLabel('Required')
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'0\''));

		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('ResearchResearchesModel', 'research', 'id'); // define a 1:many relation to Research
    }
    
    
} 

?>