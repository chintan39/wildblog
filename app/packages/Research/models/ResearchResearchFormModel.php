<?php

class ResearchResearchFormModel extends AbstractVirtualModel {
	
	var $research = null;
	var $questionNumber = 1;
	
    function __construct($id = false, $research = null) {
    	parent::__construct($id);
    	if ($research !== null) {
    		$this->setResearch($research);
    		$this->loadMetaData();
    	}
    }
    
	private function setResearch($research) {
		$this->research = $research;
	}
	
	private function loadMetaData() {
		$this->research->addNonDbProperty('questions');
		$this->research->questions = $this->research->Find('ResearchQuestionsModel');
		$this->id = $this->research->id;
		if ($this->research->questions) {
			foreach ($this->research->questions as $q) {
				$meta = new ModelMetaItem('question' . $this->questionNumber++);
				$meta->setLabel($q->title);
				$meta->setType($q->type);
				$meta->setDescription($q->text);
				$meta->question = $q;
				if ($meta->getType() == Form::FORM_SELECT_FOREIGNKEY || $meta->getType() == Form::FORM_RADIO_FOREIGNKEY
					|| $meta->getType() == Form::FORM_MULTISELECT_FOREIGNKEY) {
				$options = ResearchOptionsModel::makeOptionsFromItemsSelect($q->Find('ResearchOptionsModel'));
					$meta->setOptions($options);
					$meta->setOptionsMustBeSelected(true);
				}
				switch ($meta->getType()) {
					case Form::FORM_RADIO_FOREIGNKEY:
						$meta->setType(Form::FORM_RADIO);
						break;
					case Form::FORM_MULTISELECT_FOREIGNKEY:
					case Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE:
						$meta->setType(Form::FORM_MULTISELECT);
						break;
					case Form::FORM_SELECT_FOREIGNKEY:
						$meta->setType(Form::FORM_SELECT);
						break;
					case Form::FORM_INPUT_TEXT:
						break;
					case Form::FORM_INPUT_NUMBER:
						$meta->addRestrictions(Restriction::R_NUMBER);
						break;
				}
				$itemName = $meta->getName();
				$this->addMetaData($meta);
			}
		}
	}

	public function Save($forceSaving=false) {
		$newFilling = new ResearchFillingsModel();
		$newFilling->Connect($this->research);
		$newFilling->Save();
		foreach ($this->getMetadata() as $meta) {
			$newAnswer = new ResearchAnswersModel();
			$itemName = $meta->getName();
			$newAnswer->value = $this->$itemName;
			$newAnswer->Connect($newFilling);
			$newAnswer->Connect($meta->question);
			$newAnswer->Save();
		}
	}

}

?>
