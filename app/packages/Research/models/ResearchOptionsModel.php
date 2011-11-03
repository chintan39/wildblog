<?php

class ResearchOptionsModel extends AbstractCodebookModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'options';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
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
    	
	    $this->addCustomRelation('ResearchQuestionsModel', 'question', 'id'); // define a 1:many relation to Research
    }
    
    public static function makeOptionsFromItemsSelect($optionItems) {
		$options = array();
		if ($optionItems) {
			foreach ($optionItems as $o) {
				$options[] = array(
					'id' => $o->id,
					'value' => $o->title,
					);
			}
		}
		return $options;
    }

    public static function makeOptionsFromItems($optionItems) {
		$options = array();
		if ($optionItems) {
			foreach ($optionItems as $o) {
				$options[$o->id] = $o->title;
			}
		}
		return $options;
    }
} 

?>